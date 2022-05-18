§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§
  ABOUT TECHNOLOGIC CHOICES FOR THE EXERCISES
§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§§

==============================================================
 TASK #1 - Docker.
==============================================================
STATUS: COMPLETED (except self signed certificate browser validation)

Docker configuration files define their own technologic standards and requirements.
So there is no technologic choice to mention here. Just using what's required.

One thing I let unachieved is the browser validation of the self-signed certificate.
I hoped to take another look at it but finally could'nt.


==============================================================
 TASK #2 - Problem solving.
==============================================================
STATUS: COMPLETED
NB : Sorry for the "1-2 lines", it seems I can't tame my literary side...

Main technical choice
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
PHP is the natural choice here for me because it's the language I know the best.
That's also the one used for an API-Platform/Swagger tutorial I did at fall of 2020.
So it seemed an appropriate choice to be easy integrable for next exercise
(even if I did'nt planned to do it then, by lack of time).

I will **NOT** use Laravel because I haven't used it yet, and I have not enough time to learn it for this test.
That's why I'll use plain PHP, which is sufficient for the requirements of TASK #2.
The code therefore will not include unit tests nor functionnal tests, which could ideally have been more easily included using Laravel.
Laravel would certainly provide a better and more extensible and interoperable "real world" framework solution.

"Why do we want to avoid using the eval() function ?"
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
The eval function in PHP and other languages is known to be a security hole because it allows no control on what is evaluated and executed.
It can virtually be any interpretable instructions and therefore could have imprevisible results in case input data has not been perfectly filtered.

Also, the aim of this exercise is about the way to handle composition of a custom functionnal vocabulary which gets interpreted
in a fully customisable maneer. I did'nt saw clearly that concern at first, and I still wonder if my choice of using PHP inclusion
interpretation mechanism is entirely compliant with this second requirement. I think it is at least with the first security concern.

Other technical considerations
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
I sticked to a functionnal reproduction of the example. The vocabulary of patterns is constituted by the string treatment methods
carried by the InputParam object. It can be easyly extended, by code addition or by class inheritance.
And this set of methods is an API-like approach, compatible in that with the swagger next step.
But the "email processor" driver script remains essentially procedural.
NB: the double tld in the result also comes from the example inputs, but can be easily cleared.

To handle the logical operators given, I just used PHP builtin support, as I see no benefit in re-inventing this wheel.

In the JSON output, the "value" property is encapsulated in an "attributes" top-level property, and a top-level "type" property
is added. This is to comply with the "application/vnd.api+json" MIME type requirements on "ressource objects"
(see: https://jsonapi.org/format/#document-resource-objects).


==============================================================
 TASK #3 - Bonus.
==============================================================
STATUS: UNACHIEVED

At first, given the time that I had, I decided to skip this part.
So I did'nt thought too much at it when developping TASK #2.
I realised this was wrong when I finally began to work on it.
My code from TASK #2 being half procedural was not API ready.
So I tried to imagine an API structure able to accomodate the requirements TASK #2.
And I had to refresh my knowledge on API Platform, sadly unused since
I self-trained on it almost 2 years ago.
The result of this is that I could make the basic structure for this API,
but it's not yet fonctionnal : it crashes on the passing of arguments, at this stage.
A matter of tweaking annotations vs Symfony auto-wiring, I suppose...
To gain time, I've built on the tutorial I once did, with no components update.
Which means it's on API-Platform 2.4, so no PATCH method...
I brainlessly made multi-target methods for creating and deleting multiple instances of InputParam
and Expression objects. Theese are not REST/HTTP compliant. They could be treated as
a PATCH on the collections with API Platform >=2.5.

