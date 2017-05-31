# SelectSpecify
SelectSpecify is a jQuery-plugin that turns a select-element into a linking table that allows for an extra attribute, such as a keyword or any other extra attribute of the link.

![](https://github.com/blekerfeld/SelectSpecify/blob/master/docs/images/image1.PNG?raw=true)

## Requirements
* jQuery 1.3+
* Clone the repository (or alternativly download its content) and add `jquery.selectspecify.js` and `jquery.selectspecify.css` to your page

# Simple usage

## HTML

```html
<select class='example'>
  <option value='1'>Apple</option>
  <option value='2'>Pear</option>
</select>
```

#### Title
A title can be added by setting the `data-heading`-attribute of the original `<select>`-element.

## Javascript

```html
<script type='text/javascript'>
  $('.example').SelectSpecify();
</script>
```

### Getting the value

The links (and attributes) are stored inside a javascript object that can be accessed like this:

```html
<script type='text/javascript'>
  $('.example').data('storage');
</script>
```

After being send to the server, to PHP via AJAX for example, the example shown above (with 'apple' being selected, the being attribute 'cake'), looks like this:

```
array (size=1)
  0 => 
    array (size=2)
      'value' => string '1' (length=1)
      'keyword' => string 'cake' (length=4)
```	

## Options

### Pre-loaded items

Preloading options is a bit more complex than just giving `selected` attributes to the options, because SelectSpecify allows the same option to be selected more than once. Preloaded options need to be specified seperatly within the select element like this:

```html
<select class='example'>
  <option role='load' data-value='1' data-attr='cake' /> 
  <option value='1'>Apple</option>
  <option value='2'>Pear</option>
</select>
``` 

`<option>` elements with a ` role='load' ` attribute, are rendered as pre-loaded values, the `data-value` attribute holds the value, that means there needs to be a regular option-element with the same value. The `data-attr` attribute holds the specification (i.e. the keyword).

### All available options

#### Attribute
There are three options related to the attribute (the extra bit of information on each link). `attributeName`, `attributeSurface` and `attributeElement`. 

##### attributeName and attributeSurface

```javascript
{
	'attributeName': 'score',	// The name that is visable in the array returned
	'attributeSurface': 'Simularity score'	// The name that is rendered as label
}
```
##### attributeElement
Instead of a textbox holding the attributive value, any other element that is capable of holding a value is allowed. For example another `<select>`-element with predefined options might be provided:

```javascript
{
	'attributeElement': '<select>
		<option value="0%">0%</option><option value="25%">25%</option>
		<option value="50%">50%</option><option value="75%">75%</option>
		<option value="100%">100%</option>
	</select>',				
}
```

#### Theme and width
A theme class can be easily added by setting the `theme`-option. At the moment of writing no additional themes are shipped with SelectSpecify, but it is very much possible to create your own. Just copy the styles in `jquery.selectspecify.css` and add a theme class to the main-container (`select-specify.yourthemename`) and style how you like.

```javascript
{
	'theme': 'yourtheme',			
}
```


The width is taken from the original `<select>`-element, `auto` is assumed if there is no width. The width can be overriden by giving `width` as an option:

```javascript
{
	'width': '20%',			
}
```

#### Duplicates
By default duplicates are not allowed, that means that every option in the original select-element can only be selected and attributed once. Setting the option `noDuplicates` to `false` makes it possible to select the same option multiple times with different attributes. 

```javascript
{
	'noDuplicates': false,			
}
```

#### Select2-intergration
It is very easy to use SelectSpecify together with Select2, just set `select2` to `true` and specify any options for select2 in `select2options`.

```javascript
{
	'select2': true,
	'select2options': {
		// Any additional select2 options
	}			
}
```

#### Placeholder
By default the placeholder is 'Add item', this can be changes through the `placeholder`-option
```javascript
{
	'placeholder': 'Add links',			
}
```

#### Localisation
The strings inside the elements can be easily changed by setting some options.
```javascript
{
itemLabelText: 'Item',
    addButtonText: 'Add',
    removeButtonText: 'Remove',
    saveButtonText: 'Save',
    placeholder: 'Add items',
}
```