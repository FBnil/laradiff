# LaraDiff - a Unicode diff tool for Laravel

This package allows easy comparison of two texts and yields output in plain text, table and html format.

The [Laravel-Diff](https://github.com/vi-kon/laravel-diff) package has a broken diff algorithm (as of dec 2017) and did not support Unicode. The algorithm used is the [diff implementation of Stephen Morley](http://code.stephenmorley.org/php/diff-implementation/) wrapped in a composer implementation.

Stephen liked static classes a lot, so compareText() and compareFiles() are static, and compare() is non-static.

Warning: Alpha code:
I am also still molding it to be better code (exceptions, checks for is_readable() etc).

![examples](diff_looks.jpg?raw=true "Different looks with basic css")


## Table of content

* [Features](#features)
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
* [CSS Styling](#css-styling)

---
[Back to top](#laravel-diff-tool)

## Features

* compare two (multiline) **strings** 
* compare two text **files**
* Unicode support
* Outputs a html snippet you can embed into a `<div>`
* the html snippet has `<ins>` and `<del>` tags which you can apply your own CSS to.
* The output can be a side-by-side `<table>`, a line by line diff `<span>` or a minimal word analysis diff. 

## Installation

TODO: add this package to packagist for this to work:

Using `composer`:

```bash
composer require FBnil/laradiff
```

---
[Back to top](#laravel-diff-tool)

## Configuration

As this is not in Packagist, you need to add this to your composer.json file:

```php
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/FBnil/laradiff.git"
    }
],
```

And then run `composer update`

TODO: add a serviceProvider

If you have Laravel >5.5 installed, that's it. No additional configuration has to be done.

For Laravel versions older than 5.5, you'll need to register the service provider, in your `config/app.php`:

```php
'providers' => [
	...
	Laradiff\DiffServiceProvider::class
]
```


---
[Back to top](#laravel-diff-tool)

## Usage

Simple oneliner:

```php
$htmlSnippet = \Laradiff\Diff::compareFiles($file1, $file2)->toHtml();
```

As a reusable object:
```php
use Laradiff\Diff as Laradiff; # allow new LaraDiff();
$d = new Laradiff();
$d->compare("a\nb", "A\nb");
echo $d->toString();
```


Other examples (statically called):

```php
use \Laradiff\Diff as Diff;
// Compare string line by line
$diff = Diff::compareText("hello\n!", "hello\nworld\n!");
// Outputs span with ins and del HTML tags. You can embed this in a div.
$html = $diff->toHTML();

// Outputs a side-by-side <table> 
$html = $diff->toTable();

// Output is text; similar to the Unix command "diff -u"
$txt = $diff->toString();

// Get the raw data to do your own analysis.
$struct = $diff->toStruct();
```

Compare two file:

```php
// Compare files line by line
$diff = \Laradiff\Diff::compareFiles("a.txt", "b.txt");
echo $diff->toHTML();
if($diff->getInsertedCount() == 0 && $diff->getDeletedCount() == 0)
 echo "Files are identical!"
```

You can process the raw data:

```php
/* Returns the diff for two files. The parameters are:
*
* @param $file1      - the path to the first file
* @param $file2      - the path to the second file
* $compareCharacters - true to compare characters, and false to compare lines. Optional; defaults to false.
*/
use Laradiff\Diff as Diff;
$diff = Diff::compareFiles($file1, $file2, true);
$AoA = $diff->toStruct();
$MYTEXT = '';
foreach($AoA as $charArr){
	$char = $charArr[0];
	$stat = $charArr[1];
	if($stat == Diff::UNMODIFIED) // 0
		$stat = ' ';
	if($stat == Diff::DELETED) // 1
		$stat = '-';
	if($stat == Diff::INSERTED) // 2
		$stat = '+';
	$MYTEXT .= "$stat:$char ";
}
echo($MYTEXT);
```


---
[Back to top](#laravel-diff-tool)


## CSS Styling

A little bit of CSS is recommended to make `<ins>` and `<del>` look good, for example:

```css
del, .diffDeleted {
  text-decoration: none;
  background-color: #fbb6c2;
  color: #555;
}

ins, .diffInserted {
  text-decoration: none;
  background-color: #d4fcbc;
}

table.diff , table.diff th, table.diff td{
	border: 1px solid #EEE;
}

table.diff th, table.diff td{
	padding: 5px; /* Apply cell padding */
}
```

---
[Back to top](#laravel-diff-tool)

## Useful External Links

http://html5doctor.com/ins-del-s/
https://stackoverflow.com/questions/31857100/adding-a-gap-between-ins-and-del-using-css


---
[Back to top](#laravel-diff-tool)

## License

This package is a mix between CC and MIT License. Give me some time to sort it out.

---
[Back to top](#laravel-diff-tool)
