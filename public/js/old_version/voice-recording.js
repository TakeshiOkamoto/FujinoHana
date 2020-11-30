// *** Old version ***

// これは古いバージョンです。
//
// Microphone -> WAV -> MP3(stereo)
//
// の順に変換してアップロードするものです。
// ※FireFoxで30分以上録音するとハングアップします(笑)
//
// そもそも、「WAVファイルを生成する = メモリ使い過ぎ」です。
//

////////////////////////////////////////////////////////////////////////////////
// Global variables
////////////////////////////////////////////////////////////////////////////////

// Web Audio API
var audioCtx;
var audioSourceNode;
var analyserNode_wave; 
var tracks;

// Frequency spectrum
var canvas1;
var ctx1;
var data1;
var bufferLength;

// (new)AudioWorklet
var workletNode; 

// (old)ScriptProcessorNode
var spNode;

// Web Worker
var record_worker = new Worker('./js/old_record.js');
var mp3_worker = new Worker('./js/mp3.js');

// Timer
var timer_start;

// Control
var btn_record, btn_stop, btn_cancel;
var lbl_processing, lbl_progress, lbl_timer;
var progress_bar;

// Flags
var firstFlag  = true;
var recordingFlag = false;
var compressingFlag = false;

////////////////////////////////////////////////////////////////////////////////
// OnLoad
////////////////////////////////////////////////////////////////////////////////

window.addEventListener('load', function(){
  canvas1 = document.getElementById('MyCanvas');
  ctx1 = canvas1.getContext('2d') ;
  
  ctx1.lineWidth =  1;
  ctx1.fillStyle ='rgb(255, 255, 255)';  
  ctx1.clearRect(0, 0, canvas1.width, canvas1.height);
      
  btn_record = document.getElementById('btn_record');
  btn_stop   = document.getElementById('btn_stop');
  btn_cancel = document.getElementById('btn_cancel');
  
  lbl_processing = document.getElementById('lbl_processing');  
  lbl_progress   = document.getElementById('lbl_progress');  
  lbl_timer      = document.getElementById('lbl_timer');
    
  progress_bar = document.getElementById('progress_bar');
      
  btn_record.disabled = false;
  btn_stop.disabled   = true;   
});

////////////////////////////////////////////////////////////////////////////////
// Internal functions
////////////////////////////////////////////////////////////////////////////////

// 周波数スペクトル
function draw(){
  
  if(recordingFlag){
    requestAnimationFrame(draw); 
  }     
  
  analyserNode_wave.getFloatFrequencyData(data1);
  ctx1.fillStyle = 'rgb(255, 255, 255)';
  ctx1.fillRect(0, 0, canvas1.width, canvas1.height);
  ctx1.strokeRect(0, 0, canvas1.width, canvas1.height);
 
  // スペクトルの描画
  // 参考元：https://developer.mozilla.org/ja/docs/Web/API/Web_Audio_API/Visualizations_with_Web_Audio_API
  var barWidth = (canvas1.width / bufferLength) * 2.5;
  var posX = 0;
  
  for (var i = 0; i < bufferLength; i++) {
    var barHeight = (data1[i]+140) * 3;               
    ctx1.fillStyle = 'rgb(21,' + Math.floor(150) + ', 170)';
    ctx1.fillRect(posX, canvas1.height - barHeight / 2, barWidth, barHeight/ 2);
    posX += barWidth + 1;
  } 
}

// 録音
function record(){
   
  if(tracks) return;  
  
  progress_bar.style.display = 'none'; 
  btn_cancel.style.display   = 'block'; 
  lbl_processing.innerHTML   = document.getElementById("msg_mp3_progress").value;
  
  if(firstFlag){
    // AudioContextの生成
    audioCtx =  new AudioContext();     
    firstFlag = false;     
  }    
  
  btn_record.disabled = true;
  btn_stop.disabled   = false;
    
  var ua = window.navigator.userAgent.toLowerCase();
  var chrome = (ua.indexOf('chrome') !== -1) && (ua.indexOf('edge') === -1)  && (ua.indexOf('opr') === -1);  
 
  // Chrome/Edge(新)
  var constraints; 
  if(chrome){
    constraints = {
                    "video": false,
                    "audio": {                           
                              "mandatory": {
                                "googEchoCancellation" : false,
                                "googAutoGainControl"  : false,
                                "googNoiseSuppression" : false,
                                "googHighpassFilter"   : false   
                               },
                              "optional": []
                             }
                  };
  // FireFox/Edge(旧) 
  }else{
    constraints = {
                    "video": false,
                    "audio": {                           
                              "echoCancellation"     : false,
                              "autoGainControl"      : false,        
                              "noiseSuppression"     : false
                             }
                  };  
  }   
  
  // 将来的にウェブ標準予定  
  if("mediaDevices" in navigator && "getUserMedia" in navigator.mediaDevices){      

    navigator.mediaDevices.getUserMedia(constraints)
      .then(function (stream) {      
        
        // scriptProcessorNodeの方が安定してるよ
        if('createScriptProcessor' in audioCtx || 'createJavaScriptNode' in audioCtx){
          console.log('USE: ScriptProcessorNode');
          old_run(stream);
        }else{
          console.log('USE: AudioWorklet');
          modern_run(stream);   
        }  
                  
        /*
          // audioWorkletが存在すればそちらを使用する
          if('audioWorklet' in audioCtx){
            modern_run(stream);
          }else{
            old_run(stream);   
          }
        */
     
        // タイマー発動
        timer_start = new Date();
        record_timer();
       
    }).catch(function (err) {    
       alert(err);
       btn_record.disabled = false;
       btn_stop.disabled   = true;   
    });    
        
  // 非推奨(ウェブ標準から削除)  
  }else{    
    console.log("USE: navigator.getUserMedia()");
    
    navigator.getUserMedia(    
        constraints,
        
        function(stream) { 
           
          // scriptProcessorNodeの方が安定してるよ
          if('createScriptProcessor' in audioCtx || 'createJavaScriptNode' in audioCtx){
            console.log('USE: ScriptProcessorNode');
            old_run(stream);
          }else{
            console.log('USE: AudioWorklet');
            modern_run(stream);   
          }  
                      
          /*
            // audioWorkletが存在すればそちらを使用する
            if('audioWorklet' in audioCtx){
              modern_run(stream);
            }else{
              old_run(stream);   
            }
          */
          
          // タイマー発動
          timer_start = new Date();
          record_timer();                  
        },        

        function(err) {
          alert(err);
          btn_record.disabled = false;
          btn_stop.disabled   = true;    
        }
    );
  }
}

// 録音停止 
function stop(){
  recordingFlag = false; 
  
  // FireFoxの録音ダイアログ用
  if(!spNode && !workletNode){
    return;
  }
  
  if('createScriptProcessor' in audioCtx || 'createJavaScriptNode' in audioCtx){
    record_worker.postMessage({"state": "export"});
  }else{
    workletNode.port.postMessage({"state": "export"}); 
  }  
        
  /*
    if('audioWorklet' in audioCtx){
      workletNode.port.postMessage({"state": "export"});   
    }else{
      record_worker.postMessage({"state": "export"});
    }  
  */
 
  if(tracks){
    tracks.forEach(function(track) {
      track.stop();
    });
    tracks = null;
  }  
}

// MP3変換
function finish(stream){
  btn_stop.disabled = true; 

  console.time('MP3 conversion time');    
  progress_bar.style.display = 'block';    
  btn_cancel.style.display   = 'block';  
  
  var kbps = document.getElementById("kbps").value;
  var bitRate = 64;
  if (kbps ==  1) bitRate =  32;
  if (kbps ==  2) bitRate =  48;  
  if (kbps ==  3) bitRate =  64;
  if (kbps ==  4) bitRate =  80;
  if (kbps ==  5) bitRate =  96;
  if (kbps ==  6) bitRate = 112;
  if (kbps ==  7) bitRate = 128;
  if (kbps ==  8) bitRate = 160;
  if (kbps ==  9) bitRate = 192;
  if (kbps == 10) bitRate = 224;
  if (kbps == 11) bitRate = 256;
  if (kbps == 12) bitRate = 320;
  
  mp3_worker.postMessage({
    cmd: 'init',
    config: {'bitRate': bitRate}
  });
  
  compressingFlag = true;
  mp3_worker.postMessage({cmd: 'encode', rawInput: stream.buffer});
  mp3_worker.postMessage({cmd: 'finish'});
}

////////////////////////////////////////////////////////////////////////////////
// Timer
////////////////////////////////////////////////////////////////////////////////

// 録音タイマー
// 参考元：https://www.tagindex.com/javascript/time/timer1.html
function record_timer(){

  // 経過時間
  var passage = parseInt(((new Date()).getTime() - timer_start.getTime()) / 1000);

  var hours    = parseInt(passage / 3600);
  var minutes  = parseInt((passage / 60) % 60);
  var seconds  = passage % 60;

  // 2桁表示
  if (hours   < 10) { hours   = "0" + hours;   }
  if (minutes < 10) { minutes = "0" + minutes; }
  if (seconds < 10) { seconds = "0" + seconds; }

  if (!btn_stop.disabled && !compressingFlag){
    lbl_timer.innerHTML = hours + ':' + minutes  + ':' + seconds; 
    setTimeout("record_timer()", 1000);
  }
}

////////////////////////////////////////////////////////////////////////////////
// Web worker
////////////////////////////////////////////////////////////////////////////////

mp3_worker.onmessage = function (event) {
  
  // 変換完了
  if (event.data.cmd == 'end') {
    console.timeEnd('MP3 conversion time');         
    compressingFlag = false;
    lbl_processing.innerHTML = document.getElementById("msg_mp3_upload").value;   
       
    var stream = new Blob(event.data.buf, {type: 'audio/mp3'});    

    // 反則技で再生時間(長さ)を取得して送信する
    var reader = new FileReader();
    reader.onload = function (event) {
      
      audioCtx.decodeAudioData(reader.result, function(source) {
          var xmlhttp = new XMLHttpRequest();

          // イベント
          xmlhttp.onreadystatechange = function() { 
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 

              // 成功した場合のみキャンセルを非表示にする
              btn_cancel.style.display = 'none';    
              
              // ジャンプ
              window.location.href = 'files';         
            }
                        
            if (xmlhttp.readyState == 4 && xmlhttp.status == 500) { 
              alert('ERROR : The file could not be uploaded.');  
            }
          }     
          
          // トークン
          var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          var filetitle = document.getElementById('file_title').value;
          if(!filetitle){
            filetitle = document.getElementById('untitled').value;   
          }
          var url = 'upload?time=' +  encodeURI(source.duration) + '&title=' + encodeURI(filetitle);     
          xmlhttp.open("POST", url, true); // true = 非同期的
          xmlhttp.setRequestHeader('Content-Type', 'application/octet-stream');
          xmlhttp.setRequestHeader("X-CSRF-TOKEN" , token); 
          xmlhttp.send(stream);
        },

        function(e){ alert('An error has occurred.'); 
      });
    }
    reader.readAsArrayBuffer(stream); 
    
    lbl_progress.innerHTML = '';
  }
  
  // 変換中
  if(event.data.cmd == 'progress'){
    if (compressingFlag){
      lbl_progress.innerHTML = Math.round(event.data.progress * 100) + '/100';    
    }
  }
  
  // エラー
  if(event.data.cmd == 'error'){
    lbl_progress.innerHTML = '0/100';
    alert('An error has occurred.');
  }
}

// 録音終了
record_worker.onmessage = function (event) { 
  // MP3へ変換 
  finish(event.data.stream);
}

////////////////////////////////////////////////////////////////////////////////
// Web Audio API (AudioWorklet/ScriptProcessorNode)
////////////////////////////////////////////////////////////////////////////////

// 最新のAudioWorkletを使用する
async function modern_run(stream){
  tracks = stream.getTracks(); 

  // MediaElementAudioSourceNodeの生成       
  if(audioSourceNode){
    audioSourceNode.disconnect();
  }                      
  audioSourceNode = audioCtx.createMediaStreamSource(stream);

  // AudioWorkletの読み込み
  await audioCtx.audioWorklet.addModule("./js/modern_record.js");

  // AudioWorkletNodeを取得する
  if(workletNode){
    workletNode.disconnect();
  }        
  workletNode = new AudioWorkletNode(audioCtx, "MyWorklet");
   
  // 録音終了 
  workletNode.port.onmessage = function(e){  
    // MP3へ変換  
    finish(e.data.stream);
  };
  workletNode.port.start();

  workletNode.port.postMessage({"state": "record", "sampleRate": audioCtx.sampleRate});

  // AnalyserNodeの生成(周波数スペクトル) 
  if(analyserNode_wave){
    analyserNode_wave.disconnect();
  }            
  analyserNode_wave = audioCtx.createAnalyser();     
  analyserNode_wave.fftSize = 256;  
  
  bufferLength = analyserNode_wave.frequencyBinCount;
  data1 = new Float32Array(bufferLength); 
  
  audioSourceNode.connect(analyserNode_wave);
  analyserNode_wave.connect(workletNode);
  workletNode.connect(audioCtx.destination);
  
  recordingFlag = true; 
    
  draw();  
}

// 廃止予定のScriptProcessorNodeを使用する
function old_run(stream){
  var numChannels = 2;  
  
  tracks = stream.getTracks(); 
  
  // 初期化  
  record_worker.postMessage({"state": "init", "sampleRate": audioCtx.sampleRate});

  // MediaElementAudioSourceNodeの生成       
  if(audioSourceNode){
    audioSourceNode.disconnect();
  }  
  audioSourceNode = audioCtx.createMediaStreamSource(stream); 
  
  // ScriptProcessorNodeの生成  
  if(spNode){
    spNode.disconnect();
  }      
  spNode = (audioCtx.createScriptProcessor || 
              audioCtx.createJavaScriptNode).
                call(audioCtx, 4096, numChannels, numChannels);  

  // 波形の生データが出力される
  spNode.onaudioprocess = function (e) {       

    if (!recordingFlag) return;

    var buffer = [];
    for (var channel = 0; channel < numChannels; channel++) {
      buffer.push(e.inputBuffer.getChannelData(channel));
    }
     
    record_worker.postMessage({"state": "record", "data": buffer});
  };
  
  // AnalyserNodeの生成(周波数スペクトル) 
  if(analyserNode_wave){
    analyserNode_wave.disconnect();
  }            
  analyserNode_wave = audioCtx.createAnalyser();     
  analyserNode_wave.fftSize = 256;  
  
  bufferLength = analyserNode_wave.frequencyBinCount;
  data1 = new Float32Array(bufferLength);  
  
  audioSourceNode.connect(analyserNode_wave);
  analyserNode_wave.connect(spNode);  
  spNode.connect(audioCtx.destination);
      
  recordingFlag = true;  
  
  draw();  
}
