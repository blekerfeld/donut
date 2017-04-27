## DIS: donut inflection syntax

Donut uses a custom written two-level compiler to handle inflections and adaptations to phonological context. The inflection is generated on the lowest level and the phonological rules are used to generate the surface form. 

```
input:				wonen

inflection generates ↓

	internal: 			ge{won+D

phonological rules generate ↓

	surface:			gewoond

```

### Morphological syntax

#### Input

An inflection looks as follows:

```
ge-!ver-!be[-en]D
```

The example is the inflection rule for a regular past participle in Dutch, it accepts an infinitive regular verb as input. Let's break down the syntax:

**Stem**
The regular verb stem in Dutch is formed just by taking the infinitve ending *-en* away. The rules for forming the stem are placed inside `[square brackets]`. There can be multiple stem rules, separeated by a `; semicolon`. There are two operators allowed inside the stem brackets:

`+` - adds something to the input word.
`-` - removes something to the input word.

The stem forming rules allow for negative condition, which means nothing more that when the condition is met, the operation will not be preformed. The operator for negative condtions is `-!^` for a check from the start of a word and `-!$` to check from the end of a string. See the following example, where *-en* is only removed from the end of a word, if the word does not end in *-eren*. 

```
[-en-!$eren]
```

**Prefixes and suffixes**

Prefixes and suffixes are placed before and after the stem at their respective possitions. Multiple affixes are allowed, separated by a `; semicolon`. The advantage of having them seperated is that the phonological rules can be more exact that way. 

Each affix is added to the word with a seperator: `{` for prefixes and `+` for suffixes. These seperators can be used in the phonogical rules to match on. That is really handy when a phonological rule only applies when there is a certain ending, that way it is easy for the two level compiler to know whether a morpheme-boundary is present.


Affixes allow for negative condtions, just like the stem forming rules. The only difference is that the position for the check does *not* have to be specified, but it is allowed, if for example a prefix is only added when the stem ends in a certain vowel. If no position is given, the front is assumed in prefixes and the back in suffixes.

`ge-!ver-!be[stem]`-→ The Dutch prefix *ge-* is only added to the past participle if the stem does not start with *ver-* or *be-*  

**Infixes**

Sadly the two level compiler does not yet allow for infixes, if you have any suggestions in how this could be achieved, please contact me.

**Variables and ignored characters**

Uppercase letters can be used as variables that later can be changed by the phonological rules. The two level compiler automatically outputs in lowercase. The past participle example uses an uppercase **D** as a variable, that is changed to either **d** or **t** depending on the phonological context. 

If a certain letter is not used as a variable in a phonological rule, it can be used to avoid changes by phonological rules, most of the time the lexical form of some words may need such a letter. In Dutch, for example, all *e*'s are doubled in ceratin phonological context (lezen -> lees), if this needs to be avoided (i.e. in **schilderen* -> *schilderde* **not** *schildeerde*), the lexical form can be `schildEren`, that way that E does not get affected.

Future versions of DIS might have a different notation for variables, for example something like `&d`.

### Phonological syntax
