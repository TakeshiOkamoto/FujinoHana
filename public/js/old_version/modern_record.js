// Recorderjs
// Copyright © 2013 Matt Diamond
// https://github.com/mattdiamond/Recorderjs
// ※一部、改変して使用しています。

class MyWorklet extends AudioWorkletProcessor { 
  
  // コントラスタ
  constructor (options) {
    super(options);
    
    this.recLength;
    this.recBuffers;
    this.numChannels = 2; // ステレオ
    this.sampleRate;
    this.firstflg = true;
    
    this.state = "";
    this.port.onmessage = event => {
      if (event.data.state){
        this.state = event.data.state;
      }
      if (event.data.sampleRate){
        this.sampleRate = event.data.sampleRate;
      }      
    }
    this.port.start();
  }
  
  // 波形の生データが出力される
  process (inputs, outputs, parameters) {
      
      // 録音
      if(this.state == "record"){
        
         if(this.firstflg){
           this.recLength =0;
           this.recBuffers = [];
           this.recBuffers[0] = [];
           this.recBuffers[1] = [];       
           this.firstflg = false;
         }
         
         // FireFoxは最初は空になる ※FireFoxのバグ？         
         if (inputs[0].length != 0){
           var inputBuffer = [];
           inputBuffer[0] = new Float32Array(inputs[0][0].length);
           inputBuffer[1] = new Float32Array(inputs[0][1].length);

           inputBuffer[0].set(inputs[0][0]);   
           inputBuffer[1].set(inputs[0][1]);   

           this.record(inputBuffer);
        }
      }       
      
      // ファイルの生成
      if(this.state == "export"){ 
          
          var buffers = [];
          
          // 2チャンネルをバッファへ
          for (var channel = 0; channel < this.numChannels; channel++) {
            buffers.push(this.mergeBuffers(this.recBuffers[channel], this.recLength));
          }
          
          // L/Rを結合する
          var interleaved = this.interleave(buffers[0], buffers[1]);
          
          // WAVのエンコード
          var dataview = this.encodeWAV(interleaved);
          
          this.port.postMessage({'stream': dataview});
          
          this.firstflg = true;
          return false;
      }        
            
      if(this.state == "stop"){
        this.firstflg = true;
        return false;
      }        
      
      return true;             
    }    

    record(inputBuffer) {
          for (var channel = 0; channel < this.numChannels; channel++) {
              this.recBuffers[channel].push(inputBuffer[channel]);
          }
          this.recLength += inputBuffer[0].length;
    }    
    
    floatTo16BitPCM(output, offset, input) {
        for (var i = 0; i < input.length; i++, offset += 2) {
            var s = Math.max(-1, Math.min(1, input[i]));
            output.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
        }
    }

    writeString(view, offset, string) {
        for (var i = 0; i < string.length; i++) {
            view.setUint8(offset + i, string.charCodeAt(i));
        }
    }
      
    encodeWAV(samples) {
        var buffer = new ArrayBuffer(44 + samples.length * 2);
        var view = new DataView(buffer);

        // RIFF identifier 
        this.writeString(view, 0, 'RIFF');
        // RIFF chunk length 
        view.setUint32(4, 36 + samples.length * 2, true);
        // RIFF type 
        this.writeString(view, 8, 'WAVE');
        // format chunk identifier 
        this.writeString(view, 12, 'fmt ');
        // format chunk length 
        view.setUint32(16, 16, true);
        // sample format (raw) 
        view.setUint16(20, 1, true);
        // channel count 
        view.setUint16(22, this.numChannels, true);
        // sample rate 
        view.setUint32(24, this.sampleRate, true);
        // byte rate (sample rate * block align) 
        view.setUint32(28, this.sampleRate * 4, true);
        // block align (channel count * bytes per sample) 
        view.setUint16(32, this.numChannels * 2, true);
        // bits per sample 
        view.setUint16(34, 16, true);
        // data chunk identifier 
        this.writeString(view, 36, 'data');
        // data chunk length 
        view.setUint32(40, samples.length * 2, true);
        
        this.floatTo16BitPCM(view, 44, samples);

        return view;
    }
                
    interleave(inputL, inputR) {
        var length = inputL.length + inputR.length;
        var result = new Float32Array(length);

        var index = 0,
            inputIndex = 0;

        while (index < length) {
            result[index++] = inputL[inputIndex];
            result[index++] = inputR[inputIndex];
            inputIndex++;
        }
        return result;
    }
                
    mergeBuffers(recBuffers, recLength) {
      var result = new Float32Array(recLength);
      var offset = 0;
      for (var i = 0; i < recBuffers.length; i++) {
          result.set(recBuffers[i], offset);
          offset += recBuffers[i].length;
      }
      return result;
  }    
}

// プロセッサーの登録
registerProcessor("MyWorklet", MyWorklet);
