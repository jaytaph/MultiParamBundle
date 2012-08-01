NoxLogicMultiParamBundle
========================

This bundle provides enables the @MultiParamConverter. It's similar to the [@paramConvert annotation](http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/annotations/converters.html),
except for the following additional features:

 - You can add multiple converters in one action.
 - You can customize the slug name
 - You can customize the entity method for fetching the slug

## Installation

Installation takes just few easy steps:

### Step 1: Add the bundle to your composer.json

If you're not yet familiar with Composer see http://getcomposer.org.
Add the MultiParamBundle in your composer.json:

```js
{
    "require": {
        "noxlogic/multiparam-bundle": "master"
    }
}
```

**Warning:**
> if you use Symfony 2.1 the require value should be master, for Symfony 2.0 use ?? [TODO]

Now tell composer to download the bundle by running the command:

``` bash
php composer.phar update noxlogic/multiparam-bundle
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new NoxLogic\Bundle\MultiParamBundle\NoxLogicMultiParamBundle(),
    );
}
```

### Step 3: Add use statements for the MultiParamBundle

In any controller that makes use of the MultiParamConverter add this use statement:

``` php
use NoxLogic\Bundle\MultiParamBundle\Configuration\MultiParamConverter;
```

That's it. You should now be ready to implement the converter based on the examples below. Of course you do need to have
some entities to make it work.

## Features

### Multiple converters

The main function for this converter is to enable multiple converters per action. Where the standard @paramConvert only
allows one variable to be converted, this converter can do multiple:

    /**
     * @route("/acme/category/{category_id}/article/{article_id}")
     *
     * @multiParamConverter("category", class="AcmeBundle:Category")
     * @multiParamConverter("article", class="AcmeBundle:Article")
     */
    public function showAction(Category $category, Article $article) { ... }


### Customize slug name

Because there are multiple converters possible, using just "id" as your slug will not work. The multiParamConverter
will find the variable name and checks for a slug named "<name>_id". If that slug isn't available, it will default to
the "id" slug.

If you need another name, you can supply this in the options:

    /**
     * @route("/acme/country/{iso3}/airport/{iatacode}")
     *
     * @multiParamConverter("airport", class="AcmeBundle:Airport", options={"id = "iatacode"})
     * @multiParamConverter("country", class="AcmeBundle:Country", options={"id = "iso3"})
     */
    public function showAction(Country $country, Airport $airport) { ... }


### Customize entity fetch method

It's possible to fetch an entity from something else than the primary key. When no additional options are given, this
is the default behaviour, but finding entities on other properties is also possible:

    /**
     * @route("/acme/country/{iso3}/airport/{iatacode}")
     *
     * @multiParamConverter("airport", class="AcmeBundle:Airport", options={"id = "iatacode", "method" = "findOneByIataCode"})
     * @multiParamConverter("country", class="AcmeBundle:Country", options={"id = "iso3", "method" = "findOneByIso3Code"})
     */
    public function showAction(Country $country, Airport $airport) { ... }




### Customize entity manager

If you have multiple entity managers and you want to use a non-default entitymanager, you can supply the
'entity_manager' option in order to change which manager is used.

    /**
     * @route("/acme/{post_id}")
     * @multiParamConverter("post", class="AcmeBundle:Post", options = {"entity_manager" = "foo"})
     */
    public function showAction(Post $post) {


As with the default paramConverter, if you use typehinting AND you only need to use the default options, you can omit
the @MultiParamConverter annotation alltogether. Both examples are identical:

    /**
     * @route("/acme/{post_id}")
     * @multiParamConverter("post", class="AcmeBundle:Post")
     */
    public function showAction(Post $post) {


    /**
     * @route("/acme/{post_id}")
     */
    public function showAction(Post $post) {


## More info

Read more about this converter on its [official homepage](http://www.noxlogic.nl/), and do not hesitate to contact me
for issues and/or bug(fixes). PR's are always welcome!

Special thanks to CruiseTravel, for whom this converter was originally written and allowed to open-source the code.
