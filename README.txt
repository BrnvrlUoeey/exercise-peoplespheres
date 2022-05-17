**************************************************************
  ABOUT TECHNOLOGIC CHOICES FOR THE EXERCISES
**************************************************************

--------------------------------------------------------------
 TASK #1 - Docker.
--------------------------------------------------------------
Docker configuration files define their own technologic standards and requirements.
So there is no technologic choice to mention here. Just using what's required.


--------------------------------------------------------------
 TASK #2 - Problem solving.
--------------------------------------------------------------
NB : Sorry for the "1-2 lines", I can't tame my literary side...

Main technical choice
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
PHP is the natural choice here for me because it's the language I know the best.
That's also the one that was used for an API-Platform/Swagger tutorial I did at fall of 2020.
So it seemed one of the appropriate choices to be easy integrable for next exercise
(even if I did'nt planned to do it then, by previsible lack of time).

I will **NOT** use Laravel because I haven't used it yet, and I have not enough time to learn it for this test.
That's why I'll just use plain PHP, which is sufficient for the requirements and the more straightforward for me.
The code therefore will not include unit tests nor functionnal tests, which could perhaps have been more easily included using Laravel.
Laravel would provide a better and more extensible "real world" framework solution.

"Why do we want to avoid using the eval() function ?"
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
The eval function in PHP and other languages is known to be a secutity hole because it allows no control on what is evaluated and executed.
It can virtually be any interpretable instructions and therefore could have imprevisible results in case input data has not been perfectly filtered.

Also, the aim of this exercise is about the way to handle composition of a custom functionnal vocabulary which gets interpreted
in a fully customisable maneer. I did'nt saw clearly that concern at first, and I still wonder if my choice of using PHP inclusion
interpretation mechanism is entirely compliant with this second requirement.
I think it is with the first security concern.

Other technical considerations
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
I sticked to a functionnal reproduction of the example and the vocabulary of patterns is then constituted by the string treatment methods
carried by the InputParam object. It can be easyly extended, whether by code addition or by class inheritance.
And it is an API-like approach, compatible in that with the swagger next step.
NB: the double tld also comes from the example inputs, but can be easily cleared.

To handle the logical operators given, I just used PHP builtin support, as I see no benefit in re-inventing this wheel.

In the JSON output, the "value" property is encapsulated in an "attributes" top-level property, and a top-level "type" property
is added. This is to conply with the "application/vnd.api+json" MIME type requirements on "ressource objects"
(see : https://jsonapi.org/format/#document-resource-objects).


--------------------------------------------------------------
 TASK #3 - Bonus.
--------------------------------------------------------------

I'll use nothing because I will not have enough time to make this.
If I did, I would have used PHP API framework API-Platform (built over Symfony) because that's the solution I already tested - and it's fine. 
A lighter solution with another technology could be better here though, because this one, built over Symfony, still carries a lot of code.
