# evol.colorpicker

evol.colorpicker is a web color picker which looks like the one in Microsoft Office 2010. It can be used inline or as a popup binded to a text box.
It is a full jQuery UI widget, supporting various configurations and themes.

## Demo

![screenshot 1](https://raw.github.com/evoluteur/colorpicker/master/screenshot1.png) ![screenshot 2](https://raw.github.com/evoluteur/colorpicker/master/screenshot2.png)

Check the [demo](http://evoluteur.github.com/colorpicker/index.html) for several examples.


## Usage

First, load [jQuery](http://jquery.com/) (v1.7 or greater), [jQuery UI](http://jqueryui.com/) (v1.8 or greater), and the plugin:

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/evol.colorpicker.min.js" type="text/javascript" charset="utf-8"></script>

The widget requires a jQuery UI theme to be present, as well as its own included base CSS file ([evol.colorpicker.css](http://github.com/evoluteur/colorpicker/raw/master/css/evol.colorpicker.css)). Here we use the "ui-lightness" theme as an example:

    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/ui-lightness/jquery-ui.css">
    <link href="css/evol.colorpicker.css" rel="stylesheet" type="text/css">

Now, let's attach it to an existing `<input>` tag:

    <script type="text/javascript">
        $(document).ready(function() {
            $("#mycolor").colorpicker();
        });
    </script>

    <input style="width:100px;" id="mycolor" />

This will wrap it into a "holder" `<div>` and add another `<div>` beside it for the color box:

	<div style="width:128px;">
	   <input style="width:100px;" id="mycolor" class="colorPicker evo-cp0" />
	   <div class="evo-colorind" style="background-color:#8db3e2"></div>
	</div>

Using the same syntax, the widget can also be instanciated on a `<div>` or a `<span>` tag to show inline. In that case the generated HTML is the full palette.


## Theming

evol.colorpicker is as easily themeable as any jQuery UI widget, using one of the [jQuery UI themes](http://jqueryui.com/themeroller/#themeGallery) or your own custom theme made with [Themeroller](http://jqueryui.com/themeroller/).


## Options

evol.colorpicker provides several options to customize its behaviour:

### color (String)

Used to set the default color value.

    $("#mycolor").colorpicker({
        color: "#ffffff"
    });

Defaults to *null*.

### history (Boolean)

Used to track selection history (shared among all instances of the colorpicker).

    $("#mycolor").colorpicker({
        history: false
    });

Defaults to *true*.

### displayIndicator (Boolean)

Used to show color value on hover and click inside the palette.

    $("#mycolor").colorpicker({
        displayIndicator: false
    });

Defaults to *true*.

### showOn (String)

Have the colorpicker appear automatically when the field receives focus ("focus"), appear only when a button is clicked ("button"), or appear when either event takes place ("both").
This option only takes effect when the color picker is instanciated on a textbox.

    $("#mycolor").colorpicker({
        showOn: "button"
    });

Defaults to *"both"*.

### strings (String)

Used to translate the widget. It is a coma separated list of all labels used in the UI. 

    $("#mycolor").colorpicker({
        strings: "Couleurs de themes,Couleurs de base,Plus de couleurs,Moins de couleurs,Palette,Historique,Pas encore d'historique."
    });

Defaults to *"Theme Colors,Standard Colors,More Colors,Less Colors,Back to Palette,History,No history yet."*.

## Events

### change.color

This event is triggered when a color is selected.

    $("#mycolor").on("change.color", function(event, color){
        $('#title').css('background-color', color);
    })

### mouseover.color

This event is triggered when the mouse mouves over a color box on the palette.

    $("#mycolor").on("mouseover.color", function(event, color){
        $('#title').css('background-color', color);
    })


## Methods

### enable()
Get the currently selected color value (returned as a string).

    $("#mycolor").colorpicker("enable");

### disable()
Get the currently selected color value (returned as a string).

    $("#mycolor").colorpicker("disable");

### isDisabled()
Get the currently selected color value (returned as a string).

    $("#mycolor").colorpicker("isDisabled");

### val([color])
Get or set the currently selected color value (as a string, ie. "#d0d0d0").

    $("#mycolor").colorpicker("val");

    $("#mycolor").colorpicker("val", "#d0d0d0");

### showPalette()
Show the palette (when using the widget as a popup).

    $("#mycolor").colorpicker("showPalette");

### hidePalette()
Hide the palette (when using the widget as a popup).

    $("#mycolor").colorpicker("hidePalette");

## Browser Support

evol.colorpicker.js has been tested for the following browsers:

  - Internet Explorer 7+
  - Firefox 9+
  - Chrome 21+
  - Safari 5+


## License

Copyright (c) 2014 Olivier Giulieri.

evol.colorpicker is released under the [MIT license](http://github.com/evoluteur/colorpicker/raw/master/LICENSE.md).

