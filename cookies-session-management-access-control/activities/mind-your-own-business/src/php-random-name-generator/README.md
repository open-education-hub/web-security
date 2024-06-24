# PHP Random Name Generator

PHP class capable of generating millions of random name combinations (first names and surnames) for use as demo data in applications and other projects.

## Requirements

PHP 5.3+

## Usage

Upload the the php-random-name-generator folder to your desired location and include the class:

```
include 'php-random-name-generator/randomNameGenerator.php';
```

Inititate the class and set the output format. Available output formats are 'array', 'associative_array' and 'json'. If no output is specified, an array will be used. See below for a sample output for each.

```
$r = new randomNameGenerator('array');
```

Generate the names by passing the number of names you want to create:

```
$names = $r->generateNames(10);
```

Now you can iterate through the data and create user accounts or do whatever you want with it (don't be evil).

### Sample Outputs

#### JSON

```
[
  {
    "first_name": "Fernande",
    "last_name": "Hauer"
  },
  {
    "first_name": "Erlinda",
    "last_name": "Thiel"
  },
  {
    "first_name": "Elena",
    "last_name": "Soleman"
  },
  {
    "first_name": "Hiroko",
    "last_name": "Froncillo"
  },
  {
    "first_name": "Jordon",
    "last_name": "Buehring"
  },
  {
    "first_name": "Verlie",
    "last_name": "Coelho"
  },
  {
    "first_name": "Amos",
    "last_name": "Wernecke"
  },
  {
    "first_name": "Chasidy",
    "last_name": "Jaskolski"
  },
  {
    "first_name": "Dollie",
    "last_name": "Estrem"
  },
  {
    "first_name": "Noma",
    "last_name": "Mends"
  }
]
```

#### Array

```
Array
(
    [0] => Stacee Scheiderer
    [1] => Ambrose Sens
    [2] => Quinton Spratte
    [3] => Jolie Kapsalis
    [4] => Barbra Krawiec
    [5] => Phylicia Eikmeier
    [6] => Walton Chalfin
    [7] => Letha Prakash
    [8] => Tu Grenke
    [9] => Brunilda Kirstein
)
```

#### Associative Array

```
Array
(
    [0] => Array
        (
            [first_name] => Annabel
            [last_name] => Mapa
        )

    [1] => Array
        (
            [first_name] => Claire
            [last_name] => Iovino
        )

    [2] => Array
        (
            [first_name] => Agripina
            [last_name] => Gillig
        )

    [3] => Array
        (
            [first_name] => Kathern
            [last_name] => Strausbaugh
        )

    [4] => Array
        (
            [first_name] => Delbert
            [last_name] => Whitescarver
        )

    [5] => Array
        (
            [first_name] => Marlon
            [last_name] => Botz
        )

    [6] => Array
        (
            [first_name] => Patrice
            [last_name] => Baller
        )

    [7] => Array
        (
            [first_name] => Teodora
            [last_name] => Semmes
        )

    [8] => Array
        (
            [first_name] => Billy
            [last_name] => Hruby
        )

    [9] => Array
        (
            [first_name] => Sammy
            [last_name] => Hess
        )

)
```