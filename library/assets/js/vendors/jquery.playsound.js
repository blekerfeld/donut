/**
 * @author Alexander Manzyuk <admsev@gmail.com>
 * Copyright (c) 2012 Alexander Manzyuk - released under MIT License
 * https://github.com/admsev/jquery-play-sound
 * Usage: $.playSound('http://example.org/sound.mp3');
**/

(function($){

  $.extend({
    playSound: function(){
    	$(document.body).css({ 'cursor': 'default' });
    	return $('<audio autoplay="autoplay" style="display: none;" controls="controls" rel="noreferrer"><source src="'+arguments[0]+'" rel="noreferrer"/></audio>').appendTo('body');
    }
  });

})(jQuery);