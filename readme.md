Tagify ExpressionEngine (EE) Plugin
===================================

Do some fancy find/replace magic stuff in your entry fields. Originally written to convert "pseudotags" to real EE tags, this plugin can convert anything within square brackets to anything else.

To use tagify with a given field, wrap the field in `{exp:tagify}{/exp:tagify}` tags.

If you want to limit the types of pseudotags that get converted, pass them in a pipe-delimited tag_name param like so: `{exp:tagify tag_name="append|current_uri"}{body}{/exp:tagify}` or `{exp:tagify tag_name="current_uri"}{some_custom_field}{/exp:tagify}`.

Standard Methods
----------------

The plugin comes with two pseudotag-conversion methods: `replace_embed` and `replace_current_uri`. 

* `replace_embed`: in the entry field, any **`[embed="some/template"]`** will be converted to `{embed="some/template"}` and parsed as such. You can also pass parameters; for example: `[embed="some/template" entry_id="555"]`.
* `replace_current_uri`: in the entry field, `[current_uri]` will be converted to the current page's uri, regardless of whether the field in which the pseudotag appears is within an entries loop or not.

Fork It
-------

Feel free to fork this plugin and write your own `replace_*` methods. Some day I'll work on making the plugin more extensible by putting the replace methods in their own file instead of the same one as the (admittedly simple) plugin logic.
