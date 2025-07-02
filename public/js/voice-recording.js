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
var realTimeWorker;

// Timer
var timer_start;

// Control
var btn_record, btn_stop, btn_cancel;
var lbl_processing, lbl_timer;
var progress_bar;

// Flags
var firstFlag  = true;
var recordingFlag = false;

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
  
  // OnLoadの前はスルー ※表示されてすぐボタンを押した時
  if(!progress_bar) return;
  
  if(firstFlag){
    // AudioContextの生成
    audioCtx =  new AudioContext();     
    firstFlag = false;     
  }    
  
  progress_bar.style.display = 'none'; 
  btn_cancel.style.display   = 'block'; 
  lbl_processing.innerHTML   = document.getElementById("msg_mp3_recording").value;  
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
                              "echoCancellation"     : false,
                              "autoGainControl"      : false,        
                              "noiseSuppression"     : false
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
        
        // scriptProcessorNodeの方が安定してる
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
       
    }).catch(function (err) {   
       console.log(err);
       alert(document.getElementById("msg_microphone").value) ;
       btn_record.disabled = false;
       btn_stop.disabled   = true;  
    });    
        
  // 非推奨(ウェブ標準から削除)  
  }else{    
    console.log("USE: navigator.getUserMedia()");
    
    navigator.getUserMedia(    
        constraints,
        
        function(stream) { 
           
          // scriptProcessorNodeの方が安定してる
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
       },        

        function(err) {
          console.log(err);
          alert(document.getElementById("msg_microphone").value) ;
          btn_record.disabled = false;
          btn_stop.disabled   = true;   
        }
    );
  }
}

// 録音停止 
function stop(){
 
  // FireFoxの録音ダイアログ用
  if(!spNode && !workletNode){
    return;
  }

  recordingFlag = false; 
  btn_stop.disabled   = true; 
 
  if('createScriptProcessor' in audioCtx || 'createJavaScriptNode' in audioCtx){
    realTimeWorker.postMessage({"cmd": 'finish'});
  }else{
    workletNode.port.postMessage({"cmd": "finish"}); 
  }  

  /*
    if('audioWorklet' in audioCtx){
      workletNode.port.postMessage({"cmd": "finish"});    
    }else{
      realTimeWorker.postMessage({"cmd": 'finish'});
    }  
  */
 
  if(tracks){
    tracks.forEach(function(track) {
      track.stop();
    });
    tracks = null;
  }  
}

function finish(event){
  
  // 変換完了
  if (event.data.cmd == 'end') {
    lbl_processing.innerHTML = document.getElementById("msg_mp3_upload").value;   

    var stream = new Blob(event.data.buf, {type: 'audio/mp3'});    

    // 反則技で再生時間(長さ)を取得して送信する
    var reader = new FileReader();
    reader.onload = function (event) {
      
      audioCtx.decodeAudioData(reader.result, function(source) {
          var xmlhttp = new XMLHttpRequest();

          // イベント
          xmlhttp.onreadystatechange = function() { 

            // アップロード成功
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 

              // 成功した場合のみキャンセルを非表示にする
              btn_cancel.style.display = 'none';    
              
              // ジャンプ
              window.location.href = 'files';         
            }
            
            // サーバー側の設定が不十分 
            if (xmlhttp.readyState == 4 && xmlhttp.status == 413) { 
              alert(document.getElementById("msg_error_413").value);  
            }

            // ログアウト済み             
            if (xmlhttp.readyState == 4 && xmlhttp.status == 419) {  
              alert(document.getElementById("msg_error_419").value);  
            }
                             
            // ファイルのアップロードに失敗                        
            if (xmlhttp.readyState == 4 && xmlhttp.status == 500) { 
              alert(document.getElementById("msg_error_500").value);  
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

        function(e){ 
          console.log(e);
          alert('An error has occurred.'); 
      });
    }
    reader.readAsArrayBuffer(stream); 
  }
}

// kbps
function getBitRate(){
  
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
  
  return bitRate;
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

  if (!btn_stop.disabled && recordingFlag){
    lbl_timer.innerHTML = hours + ':' + minutes  + ':' + seconds; 
    setTimeout("record_timer()", 1000);
  }
}

////////////////////////////////////////////////////////////////////////////////
// Web Audio API (AudioWorklet/ScriptProcessorNode)
////////////////////////////////////////////////////////////////////////////////

// AudioWorklet(最新)を使用する
async function modern_run(stream){
  tracks = stream.getTracks(); 

  // MediaElementAudioSourceNodeの生成       
  if(audioSourceNode){
    audioSourceNode.disconnect();
  }                      
  audioSourceNode = audioCtx.createMediaStreamSource(stream);

  // AudioWorkletの読み込み
  await audioCtx.audioWorklet.addModule("./js/worklet-realtime.js");

  // AudioWorkletNodeを取得する
  if(workletNode){
    workletNode.disconnect();
  }        
  
  workletNode = new AudioWorkletNode(audioCtx, "MyWorklet");
  workletNode.port.onmessage = function(e){      
    finish(e);
  };
    
  progress_bar.style.display = 'block';    
  btn_cancel.style.display   = 'block';  
  recordingFlag = true;
  
  // タイマー発動
  timer_start = new Date();
  record_timer();
  
  workletNode.port.start();
   
  var config = {"bitRate": getBitRate()};
  config.sampleRate = audioCtx.sampleRate;
  workletNode.port.postMessage({cmd: 'init', config: config});
  
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
      
  draw(); 
}

// ScriptProcessorNode(将来的に廃止予定)を使用する
function old_run(stream){

  var numChannels = 2;  
  tracks = stream.getTracks(); 
  
  var config = {"bitRate": getBitRate()};
  config.sampleRate = audioCtx.sampleRate;
  realTimeWorker = new Worker('./js/worker-realtime.js');
  realTimeWorker.onmessage = function (event) {    
    finish(event);
  }  
  realTimeWorker.postMessage({cmd: 'init', config: config});

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

  progress_bar.style.display = 'block';    
  btn_cancel.style.display   = 'block';  
  recordingFlag = true;
  
  // タイマー発動
  timer_start = new Date();
  record_timer();
    
  // 波形の生データが出力される
  spNode.onaudioprocess = function (e) {    
    if (!recordingFlag) return;
    var array = e.inputBuffer.getChannelData(0);
    realTimeWorker.postMessage({cmd: 'encode', buf: array})
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
  
  draw();  
}
