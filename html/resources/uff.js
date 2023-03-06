var tmpValue = "";

$.fn.insertRoundCaret = function(tagName) {
	return this.each(function() {
		if (tagName == 'a') {
			strStart = '<' + tagName + ' href="http://www..sk" rel="external">'
		} else if (tagName == 'img') {
			strStart = '<center><img src="/uploads/'
		} else if (tagName == 'iframe') {
			strStart = '<center><iframe width="600" height="340" src="http://www.youtube.com/embed/'
		} else if (tagName == 'img_resize_btn') {
			strStart = '<center><img src="'+tmpValue
		} else {
			strStart = '<' + tagName + '>'
		}
		if (tagName == 'img') {
			strEnd = '" alt="image" /></center>'
		} else if (tagName == 'iframe') {
			strEnd = '" frameborder="0" allowfullscreen></iframe></center>'
		} else if (tagName == 'img_resize_btn') {
			strEnd = '" alt="image" /></center>'				
		} else {
			strEnd = '</' + tagName + '>'
		}
		
		
		if (document.selection) {
			stringBefore = this.value;
			this.focus();
			sel = document.selection.createRange();
			insertstring = sel.text;
			fullinsertstring = strStart + sel.text + strEnd;
			sel.text = fullinsertstring;
			document.selection.empty();
			this.focus();
			stringAfter = this.value;
			i = stringAfter.lastIndexOf(fullinsertstring);
			range = this.createTextRange();
			numlines = stringBefore.substring(0, i).split("\n").length;
			i = i + 3 - numlines + tagName.length;
			j = insertstring.length;
			range.move("character", i);
			range.moveEnd("character", j);
			range.select()
		} else if (this.selectionStart || this.selectionStart == '0') {
			startPos = this.selectionStart;
			endPos = this.selectionEnd;
			scrollTop = this.scrollTop;
			this.value = this.value.substring(0, startPos) + strStart
					+ this.value.substring(startPos, endPos) + strEnd
					+ this.value.substring(endPos, this.value.length);
			this.focus();
			this.selectionStart = startPos + strStart.length;
			this.selectionEnd = endPos + strStart.length;
			this.scrollTop = scrollTop
		} else {
			this.value += strStart + strEnd;
			this.focus()
		}
	})
};


