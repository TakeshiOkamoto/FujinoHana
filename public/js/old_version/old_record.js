// Recorderjs
// Copyright © 2013 Matt Diamond
// https://github.com/mattdiamond/Recorderjs
// ※一部、改変して使用しています。

var recLength;
var recBuffers;
var numChannels = 2; // ステレオ
var sampleRate;

function record(inputBuffer) {
    for (var channel = 0; channel < numChannels; channel++) {
        recBuffers[channel].push(inputBuffer[channel]);
    }
    recLength += inputBuffer[0].length;
}
function floatTo16BitPCM(output, offset, input) {
    for (var i = 0; i < input.length; i++, offset += 2) {
        var s = Math.max(-1, Math.min(1, input[i]));
        output.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
    }
}

function writeString(view, offset, string) {
    for (var i = 0; i < string.length; i++) {
        view.setUint8(offset + i, string.charCodeAt(i));
    }
}
  
function encodeWAV(samples) {
    var buffer = new ArrayBuffer(44 + samples.length * 2);
    var view = new DataView(buffer);

    /* RIFF identifier */
    writeString(view, 0, 'RIFF');
    /* RIFF chunk length */
    view.setUint32(4, 36 + samples.length * 2, true);
    /* RIFF type */
    writeString(view, 8, 'WAVE');
    /* format chunk identifier */
    writeString(view, 12, 'fmt ');
    /* format chunk length */
    view.setUint32(16, 16, true);
    /* sample format (raw) */
    view.setUint16(20, 1, true);
    /* channel count */
    view.setUint16(22, numChannels, true);
    /* sample rate */
    view.setUint32(24, sampleRate, true);
    /* byte rate (sample rate * block align) */
    view.setUint32(28, sampleRate * 4, true);
    /* block align (channel count * bytes per sample) */
    view.setUint16(32, numChannels * 2, true);
    /* bits per sample */
    view.setUint16(34, 16, true);
    /* data chunk identifier */
    writeString(view, 36, 'data');
    /* data chunk length */
    view.setUint32(40, samples.length * 2, true);
    
    floatTo16BitPCM(view, 44, samples);

    return view;
}
            
function interleave(inputL, inputR) {
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
            
function mergeBuffers(recBuffers, recLength) {
    var result = new Float32Array(recLength);
    var offset = 0;
    for (var i = 0; i < recBuffers.length; i++) {
        result.set(recBuffers[i], offset);
        offset += recBuffers[i].length;
    }
    return result;
}

onmessage = function (event) {
  var state = event.data.state;

  if(state == "init"){
    recLength = 0;
    recBuffers = [];
    recBuffers[0] = [];
    recBuffers[1] = [];       
    sampleRate =  event.data.sampleRate;
  }

  if(state == "record"){     
    record(event.data.data);
  }

  if(state == "export"){
    // 作成開始
    var buffers = [];
    
    // 2チャンネルをバッファへ
    for (var channel = 0; channel < numChannels; channel++) {
      buffers.push(mergeBuffers(recBuffers[channel], recLength));
    }
    
    // L/Rを結合する
    var interleaved = interleave(buffers[0], buffers[1]);
    
    // WAVのエンコード
    var dataview = encodeWAV(interleaved);
    
    postMessage({'stream': dataview});
  }   
}