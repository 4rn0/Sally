# Documentation

Sally uses the excellent ['Faker PHP library'](https://github.com/fzaninotto/Faker) to help you fake whatever needs faking.

## Usage

Sally provides you with a lot of very specific tags to fake things with, like `{{ sally:name }}` or `{{ sally:email }}`. Some of those tags have parameters, like `{{ sally:paragraph nbSentences="3" }}` or maybe even `{{ sally:imageUrl width="800" height="600" category="cats" }}`.

Array parameters are allowed with the following notation `{{ sally:randomElement array="['a','b','c']" }}`.  DateTime objects are converted to Carbon instances for easy use in your Antler templates `{{ sally:dateTimeThisMonth timezone="Europe/Amsterdam" }}`

For a full list of of available tags, please see Faker's ['list of formatters'](https://github.com/fzaninotto/Faker#formatters) and ['language specific formatters'](https://github.com/fzaninotto/Faker#language-specific-formatters).

## Localization

Sally automatically loads Statamic's current locale, but if you're looking for something a little more specific, just add a locale parameter:

`{{ sally:firstName gender="female" locale="fr_FR" }} // Louise`