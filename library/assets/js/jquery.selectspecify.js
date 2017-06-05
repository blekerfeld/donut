/*!
 * SelectSpecify jQuery plugin
 * https://github.com/blekerfeld/cardtabs
 *
 * Released under the MIT license
 */

jQuery.fn.selectSpecify = function(options){

  // Getting the initial selector
  var selector = $(this).parents().map(function() { return this.tagName; }).get().reverse().concat([this.nodeName]).join(">");var id = $(this).attr("id");if (id) { selector += "#"+ id;}var classNames = $(this).attr("class");if (classNames) {selector += "." + $.trim(classNames).replace(/\s/gi, ".");}

  var mainClass =  $(this).attr('class');
  var count = 0;
  var currentID = 1;
  var selectedID = 0;
  var recentDeleted = 0;
  var settings = $.extend({
        theme: '',
        width: '',
    	minheight: 'initial',
        noDuplicates: true, 
        attributeName: 'keyword',
        attributeSurface: 'Keyword',
        attributeElement: '<input />',
        itemLabelText: 'Item',
        addButtonText: 'Add',
        removeButtonText: 'Remove',
        saveButtonText: 'Save',
        select2: false,
        select2options: {},
        placeholder: 'Add items',
     }, options );
  var storage = [];
  var tagsSaved = [];

  // First we need to get the select box that is the main element, clone it and remove the data-options from
  // the original, then copy the original element's inner html.
  var selectClone = $(selector).clone();
  $(selector).children('option[data-value]').each(function(){
    $(this).remove();
  });
  var selectBoxHtml = $(selector).html();

  // Now it's time to build a div with everything we need
  $(selector).replaceWith($('<div />').addClass(mainClass).addClass('select-specify ' + settings.theme).append());
  // Cloning parents width, or taking the specified width

  if(settings.width === ''){
    if(selectClone.css('width') != '0px'){
      $(selector).css('width', selectClone.css('width'));
    }
    else{
      $(selector).css('width', 'auto');
    }
  }else{
    $(selector).css('width', settings.width);
  }
  $(selector).append($('<div />').addClass('items'));
  $(selector + ' .items').append($('<span />').addClass('placeholder').html(settings.placeholder));
  $(selector).append($('<div />').addClass('input-bar'));
  $(selector + ' .input-bar').append($('<span />').addClass('value').append(settings.itemLabelText + ': <br />').
    append(
    $('<select />').addClass('proper-select').html(selectBoxHtml)
    ).append('<br />').append($('<a />').attr('href', 'javascript:void(0);').addClass('add').html(settings.addButtonText))).append(' ');
  $(selector + ' .input-bar').append($('<span />').addClass('keyword').append(settings.attributeSurface + ': <br />').append($(settings.attributeElement).addClass('proper-keyword').attr('type', 'search'))).append('<br />');
  $(selector + ' .input-bar span.value').append($('<a />').attr('href', 'javascript:void(0);').addClass('remove ' + settings.addButtonClass).html(settings.removeButtonText));
  
  $(selector + ' .items').wrap("<div class='items-outer'></div>")

  if(selectClone.data('heading')){
    $(selector + ' .items-outer').prepend($('<h3 />').append(selectClone.data('heading')));
  }

     $(selector + ' .items-outer').css('min-height', settings.minheight);
   $(selector + ' .items-outer').css('height', settings.minheight);

  // Calling select2 if needed
  if(settings.select2 == true){
    $(selector + ' .input-bar select.proper-select').select2(settings.select2options);
    $(selector + ' .input-bar select.proper-keyword').select2(settings.select2options);
  }


  function createItem(attr, value){
    $(selector + '.placeholder').hide();
    var valueText = $(selector + ' .proper-select option[value="' + value + '"]').html();
    $('div.' + mainClass + ' .items').append(
      $('<div/>')
        .attr({'id' : currentID, 'data-value': value, 'data-attr': attr})
        .addClass("item item-" + currentID)
        .append($('<span/>').addClass('value').html(valueText))
        .append(' ')
        .append($('<span/>').addClass('attr').html(attr))
        .append($('<span/>').attr('role', 'remove').attr('id', currentID).append(' x '))
    );
    currentID++;
    $(selector + ' .items').on('click', 'div.item span[role="remove"]', function(){
      $(selector + ' .items .item-' + $(this).attr('id')).slideUp(100).remove();
      update();
      $(selector + ' .input-bar .proper-keyword').val('').trigger('change');
      selectedID = 0;
      count--;
      $(selector + ' .input-bar .proper-keyword').blur().val('').trigger('change');
      recentDeleted = $(this).attr('id');
      $(selector + ' .input-bar .add').html(settings.addButtonText);
      $(selector + ' .input-bar span.value .remove').hide();
    });
    $(selector + ' .items').on('click', 'div.item', function(){
      doFocus($(this));
    });
    update();
    count++;
  }

  // First we need to find out if there are any preselected options, then select them thus
  selectClone.children('option[role="load"]').each(function(){
    createItem($(this).data('attr'), $(this).data('value'));

  });



  // Adding existing translations
  $(selector + ' .add').click(function(){

    if(selectedID != 0){
      $(selector + ' .items .item-' + selectedID).remove();
    }

    var value = $(selector + ' .input-bar .proper-select').val();
    var attr = $(selector + ' .input-bar .proper-keyword').val();
    
    currentID++;

    var $children = $(selector + ' .items').find('.item[data-value="' + value + '"]');

    if($children.length != 0 && settings.noDuplicates){
      // $(selector + ' .input-bar').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
      $(selector + ' .item[data-value="' + value + '"]').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
      return false;
    }

    createItem(attr, value);

    $(selector + ' .input-bar .proper-keyword').val('').trigger('change');

    $(selector + ' .input-bar .add').html(settings.addButtonText);

    selectedID = 0;

  });
  
  function update(){
    storage = [];
    
    count = 0;

    $(selector + ' .items').children('div.item').each(function(){
        pushThis = {};
        pushThis['value'] = $(this).data('value'),
        pushThis[settings.attributeName] = $(this).data('attr'),
        storage.push(pushThis);
        count++;
      }
    );
    
    $(selector).data('storage', storage);
    $(selector).val(storage);
    
    selectedID = 0;

    if(count == 0){
      $(selector + ' .placeholder').show();
    }
    else{
      $(selector + ' .placeholder').hide();
    }

    $(selector + ' .input-bar .add').html(settings.addButtonText);
    $(selector + ' .input-bar span.value .remove').hide();

  }


  function doFocus(obj){
    selectedID = 0;
    if(recentDeleted === obj.attr('id')){
      return false;
    }
    $(selector + ' .item.selected').removeClass('selected');
    if(selectedID === obj.attr('id')){
      doUnfocus(obj);
      return false;
    }
    obj.addClass('selected');
    selectedID = obj.attr('id');
    $(selector + ' .proper-select').val(obj.data('value')).trigger('change');
    $(selector + ' .input-bar .proper-keyword').val(obj.data('attr')).trigger('change');
    $(selector + ' .input-bar .proper-keyword').focus();
    $(selector + ' .input-bar .add').html(settings.saveButtonText);
    $(selector + ' .input-bar span.value .remove').css('display', 'inline-block');
  }
  

  function doUnfocus(obj){
    obj.removeClass('selected');
    $(selector + ' .input-bar .proper-keyword').val('');
    selectedID = 0;
    $(selector + ' .input-bar .add').html(settings.addButtonText);
    $(selector + ' .input-bar span.value .remove').hide();
  }

  function doUnselect(e){
     //Do nothing if div.items was not directly clicked
      if(e.target !== e.currentTarget) return;
    
    if(selectedID != 0){
      selectedID = 0;
      $(selector + ' .item.selected').removeClass('selected');
      $(selector + ' .input-bar .proper-keyword').val('');
      $(selector + ' .input-bar .add').html(settings.addButtonText);
      $(selector + ' .input-bar span.value .remove').hide();
    }
  }


  $(selector + ' div.items').click(function(e){
    doUnselect(e);
  });

  $(selector + ' div.items-outer').click(function(e){
    doUnselect(e);
  });

  $(selector + ' div.input-bar').click(function(e){
    doUnselect(e);
  });

  $(selector + ' .input-bar .remove').click(function(e){
    $(selector + ' div.item-' + selectedID + ' span[role="remove"]').click();
  });

  return this;
};
