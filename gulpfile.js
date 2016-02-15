var elixir = require('laravel-elixir');

elixir(function(mix) {
 mix.phpUnit(['Converter/**/*.php', 'tests/**/*.php']);
});
