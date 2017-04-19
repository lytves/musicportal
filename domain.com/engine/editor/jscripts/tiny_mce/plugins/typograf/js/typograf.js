function urlencode( str ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: AJ
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // %          note: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
    // *     example 1: urlencode('Kevin van Zonneveld!');
    // *     returns 1: 'Kevin+van+Zonneveld%21'
    // *     example 2: urlencode('http://kevin.vanzonneveld.net/');
    // *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
    // *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
    // *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
                                     
    var histogram = {}, histogram_r = {}, code = 0, tmp_arr = [];
    var ret = str.toString();
    
    var replacer = function(search, replace, str) {
        var tmp_arr = [];
        tmp_arr = str.split(search);
        return tmp_arr.join(replace);
    };
    
    // The histogram is identical to the one in urldecode.
    histogram['!']   = '%21';
    histogram['%20'] = '+';
    
    // Begin with encodeURIComponent, which most resembles PHP's encoding functions
    ret = encodeURIComponent(ret);
    
    for (search in histogram) {
        replace = histogram[search];
        ret = replacer(search, replace, ret) // Custom replace. No regexing
    }
    
    // Uppercase for full PHP compatibility
    return ret.replace(/(\%([a-z0-9]{2}))/g, function(full, m1, m2) {
        return "%"+m2.toUpperCase();
    });
    
    return ret;
}

// edit link click
function edit_source() {
  text = document.getElementById('tbefore_text');
  div = document.getElementById('tafter');
  button = document.getElementById('apply_button');

  text.value = div.innerHTML;
  div.style.display = 'none';
  text.style.display = 'block';
  button.style.display = 'block';
}

// refresh button click
function send_data() {
  text = document.getElementById('tbefore_text');
  div = document.getElementById('tafter');
  button = document.getElementById('apply_button');
  
  // if textarea is visible, we should first update data div
  if (text.style.display == 'block'){
    div.innerHTML = text.value;
    text.style.display = 'none';
   button.style.display = 'none';
    div.style.display = 'block';
  }
  
  // send data to typograf
  TypografDialog.send(div.innerHTML);
}


var TypografDialog = {
  init : function(ed) {
    before = tinyMCEPopup.getWindowArg("text");
    plugin_url = tinyMCEPopup.getWindowArg("plugin_url");
    document.getElementById("tbefore").innerHTML = before;
    
    this.send(before);
  },

  send : function(text) {
    document.getElementById('tafter').innerHTML = '<div class="loader"></div>';
    request = new XMLHttpRequest();
    request.open("POST", plugin_url + '/handler.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset:UTF-8');
    request.onreadystatechange = function(aEvt){
      if (request.readyState == 4){
        if(request.status == 200){
          document.getElementById('tafter').innerHTML = request.responseText;
        }
      }
    }
    request.send('text='+urlencode(text));
  },
  
  saveContent : function() {
    tinyMCEPopup.execCommand("mceInsertTypography", false, {
      html : document.getElementById('tafter').innerHTML,
    });
    tinyMCEPopup.close();
  }
};

tinyMCEPopup.onInit.add(TypografDialog.init, TypografDialog);
