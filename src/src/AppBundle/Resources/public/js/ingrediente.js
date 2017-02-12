$(function() {
    /**
     * Magicsuggest - autocomplete multivalue
     * @see: http://nicolasbize.com/magicsuggest/doc.html
     */
    var ingrediente = $('#ricetta_form_ingredienti').val();
    var options = {
        placeholder: 'Inserisci un ingrediente',
        data: '/ingrediente/ajax',
        displayField: 'nome',
        valueField: 'id'
    };

    if (ingrediente.length > 0)
        options.value = ingrediente.split(';').map(Number);

    $('#ricetta_form_magicsuggest').magicSuggest(options);
});