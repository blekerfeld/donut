

function with_jquery(f) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.textContent = "(" + f.toString() + ")(jQuery)";
    document.body.appendChild(script);
};


$("<style>.IPAHolder kbd{font-size:13px; cursor:default}\
.IPAKey{width: 22px;\
height: 26px;\
color: #333;\
margin-bottom: 2px;\
margin-top: 2px;\
margin-right: 2px;\
padding-left:1px;\
padding-right:1px;\
font-size: 13px;}\
.IPAKey:hover{background-color:#DDDDDD}\
.IPAHolder{text-wrap:none;white-space: nowrap;}\
.IPALabel{display: inline-block;\
vertical-align: middle;\
-moz-border-radius: 9px;\
border-radius: 9px;\
-webkit-border-radius: 9px;\
text-align: center;\
margin-right: 3px;\
margin-left: 3px;\
background-color: #AAAAFF;\
color: white;\
width: 17px;\
line-height: 17px;\
}\
</style>").appendTo('head');
var IPAKeyboard={};
IPAKeyboard.data=[ { "chars" : [ { "html" : "ɑ",
          "inserter" : "ɑ",
          "name" : "a script",
          "title" : "open back unrounded vowel "
        },
        { "html" : "æ",
          "inserter" : "æ",
          "name" : "ae",
          "title" : "near-open front unrounded vowel "
        },
        { "html" : "ɐ",
          "inserter" : "ɐ",
          "name" : "a upside",
          "title" : "near-open central vowel "
        },
        { "html" : "ɑ̃",
          "inserter" : "ɑ̃",
          "name" : "a script nasalized",
          "title" : "nasalized open back unrounded vowel "
        }
      ],
    "key" : "A",
    "letter" : "A"
  },
  { "chars" : [ { "html" : "β",
          "inserter" : "β",
          "name" : "beta",
          "title" : "voiced bilabial fricative "
        },
        { "html" : "ɓ",
          "inserter" : "ɓ",
          "name" : "b hook",
          "title" : "voiced bilabial implosive "
        },
        { "html" : "ʙ",
          "inserter" : "ʙ",
          "name" : "B small",
          "title" : "bilabial trill "
        }
      ],
    "key" : "B",
    "letter" : "B"
  },
  { "chars" : [ { "html" : "ɕ",
          "inserter" : "ɕ",
          "name" : "c loop",
          "title" : "voiceless alveo-palatal fricative "
        },
        { "html" : "ç",
          "inserter" : "ç",
          "name" : "c cedil",
          "title" : "voiceless palatal fricative "
        }
      ],
    "key" : "C",
    "letter" : "C"
  },
  { "chars" : [ { "html" : "ð",
          "inserter" : "ð",
          "name" : "eth",
          "title" : "voiced dental fricative "
        },
        { "html" : "d͡ʒ",
          "inserter" : "d͡ʒ",
          "name" : "dzh",
          "title" : "voiced postalveolar affricate "
        },
        { "html" : "ɖ",
          "inserter" : "ɖ",
          "name" : "d hook low",
          "title" : "voiced retroflex plosive "
        },
        { "html" : "ɗ",
          "inserter" : "ɗ",
          "name" : "d hook high",
          "title" : "voiced alveolar implosive "
        }
      ],
    "key" : "D",
    "letter" : "D"
  },
  { "chars" : [ { "html" : "ə",
          "inserter" : "ə",
          "name" : "schwa",
          "title" : "mid-central vowel "
        },
        { "html" : "ɚ",
          "inserter" : "ɚ",
          "name" : "schwa rhotic",
          "title" : "rhotacized mid-central vowel "
        },
        { "html" : "ɵ",
          "inserter" : "ɵ",
          "name" : "schwa closed",
          "title" : "close-mid central rounded vowel "
        },
        { "html" : "ɘ",
          "inserter" : "ɘ",
          "name" : "schwa upside",
          "title" : "close-mid central unrounded vowel "
        }
      ],
    "key" : "E",
    "letter" : "E"
  },
  { "chars" : [ { "html" : "ɛ",
          "inserter" : "ɛ",
          "name" : "epsilon",
          "title" : "open-mid front unrounded vowel "
        },
        { "html" : "ɜ",
          "inserter" : "ɜ",
          "name" : "epsilon flipped",
          "title" : "open-mid central unrounded vowel "
        },
        { "html" : "ɝ",
          "inserter" : "ɝ",
          "name" : "epsilon flipped rhotic",
          "title" : "rhotacized open-mid central unrounded vowel "
        },
        { "html" : "ɛ̃",
          "inserter" : "ɛ̃",
          "name" : "epsilon nasalized",
          "title" : "nasalized open-mid front unrounded vowel "
        },
        { "html" : "ɞ",
          "inserter" : "ɞ",
          "name" : "epsilon flipped closed",
          "title" : "open-mid central rounded vowel "
        }
      ],
    "key" : "3",
    "letter" : "3"
  },
  { "chars" : [ { "html" : "ɠ",
          "inserter" : "ɠ",
          "name" : "g hook",
          "title" : "voiced velar implosive "
        },
        { "html" : "ɢ",
          "inserter" : "ɢ",
          "name" : "G small",
          "title" : "voiced uvular plosive "
        },
        { "html" : "ʛ",
          "inserter" : "ʛ",
          "name" : "G small hook",
          "title" : "voiced uvular implosive "
        }
      ],
    "key" : "G",
    "letter" : "G"
  },
  { "chars" : [ { "html" : "ɥ",
          "inserter" : "ɥ",
          "name" : "h upside",
          "title" : "labial-palatal approximant "
        },
        { "html" : "ɦ",
          "inserter" : "ɦ",
          "name" : "h hook",
          "title" : "voiced glottal fricative "
        },
        { "html" : "ħ",
          "inserter" : "ħ",
          "name" : "h barred",
          "title" : "voiceless pharyngeal fricative "
        },
        { "html" : "ɧ",
          "inserter" : "ɧ",
          "name" : "h low hook",
          "title" : "Sje-sound "
        },
        { "html" : "ʜ",
          "inserter" : "ʜ",
          "name" : "H small",
          "title" : "voiceless epiglottal fricative "
        }
      ],
    "key" : "H",
    "letter" : "H"
  },
  { "chars" : [ { "html" : "ɪ",
          "inserter" : "ɪ",
          "name" : "I small",
          "title" : "near-close near-front unrounded vowel "
        },
        { "html" : "ɪ̈",
          "inserter" : "ɪ̈",
          "name" : "schwi",
          "title" : "near-close central unrounded vowel "
        },
        { "html" : "ɨ",
          "inserter" : "ɨ",
          "name" : "i barred",
          "title" : "close central unrounded vowel "
        }
      ],
    "key" : "I",
    "letter" : "I"
  },
  { "chars" : [ { "html" : "ʝ",
          "inserter" : "ʝ",
          "name" : "j loop",
          "title" : "voiced palatal fricative "
        },
        { "html" : "ɟ",
          "inserter" : "ɟ",
          "name" : "j barred",
          "title" : "voiced palatal plosive "
        },
        { "html" : "ʄ",
          "inserter" : "ʄ",
          "name" : "j mirrored",
          "title" : "voiced palatal implosive "
        }
      ],
    "key" : "J",
    "letter" : "J"
  },
  { "chars" : [ { "html" : "ɫ",
          "inserter" : "ɫ",
          "name" : "l wave",
          "title" : "velarized alveolar lateral approximant "
        },
        { "html" : "ɬ",
          "inserter" : "ɬ",
          "name" : "l loop",
          "title" : "voiceless alveolar lateral fricative "
        },
        { "html" : "ʟ",
          "inserter" : "ʟ",
          "name" : "L small",
          "title" : "velar lateral approximant "
        },
        { "html" : "ɭ",
          "inserter" : "ɭ",
          "name" : "l long",
          "title" : "retroflex lateral approximant "
        },
        { "html" : "ɮ",
          "inserter" : "ɮ",
          "name" : "lzh",
          "title" : "voiced alveolar lateral fricative "
        }
      ],
    "key" : "L",
    "letter" : "L"
  },
  { "chars" : [ { "html" : "ɱ",
          "inserter" : "ɱ",
          "name" : "m hook",
          "title" : "labiodental nasal "
        } ],
    "key" : "M",
    "letter" : "M"
  },
  { "chars" : [ { "html" : "ŋ",
          "inserter" : "ŋ",
          "name" : "ng",
          "title" : "velar nasal "
        },
        { "html" : "ɲ",
          "inserter" : "ɲ",
          "name" : "n hook left",
          "title" : "palatal nasal "
        },
        { "html" : "ɴ",
          "inserter" : "ɴ",
          "name" : "N small",
          "title" : "uvular nasal "
        },
        { "html" : "ɳ",
          "inserter" : "ɳ",
          "name" : "n hook right",
          "title" : "retroflex nasal "
        }
      ],
    "key" : "N",
    "letter" : "N"
  },
  { "chars" : [ { "html" : "ɔ",
          "inserter" : "ɔ",
          "name" : "o open",
          "title" : "open-mid back rounded vowel "
        },
        { "html" : "œ",
          "inserter" : "œ",
          "name" : "oe",
          "title" : "open-mid front rounded vowel "
        },
        { "html" : "ø",
          "inserter" : "ø",
          "name" : "o stroke",
          "title" : "close-mid front rounded vowel "
        },
        { "html" : "ɒ",
          "inserter" : "ɒ",
          "name" : "a script upside",
          "title" : "open back rounded vowel "
        },
        { "html" : "ɔ̃",
          "inserter" : "ɔ̃",
          "name" : "o open nasalized",
          "title" : "nasalized open-mid back rounded vowel "
        },
        { "html" : "ɶ",
          "inserter" : "ɶ",
          "name" : "OE small",
          "title" : "open front rounded vowel "
        }
      ],
    "key" : "O",
    "letter" : "O"
  },
  { "chars" : [ { "html" : "ɸ",
          "inserter" : "ɸ",
          "name" : "phi",
          "title" : "voiceless bilabial fricative "
        } ],
    "key" : "P",
    "letter" : "P"
  },
  { "chars" : [ { "html" : "ɾ",
          "inserter" : "ɾ",
          "name" : "r tap",
          "title" : "alveolar tap "
        },
        { "html" : "ʁ",
          "inserter" : "ʁ",
          "name" : "R small upside",
          "title" : "voiced uvular fricative "
        },
        { "html" : "ɹ",
          "inserter" : "ɹ",
          "name" : "r upside",
          "title" : "alveolar approximant "
        },
        { "html" : "ɻ",
          "inserter" : "ɻ",
          "name" : "r upside hook",
          "title" : "retroflex approximant "
        },
        { "html" : "ʀ",
          "inserter" : "ʀ",
          "name" : "R small",
          "title" : "uvular trill "
        },
        { "html" : "ɽ",
          "inserter" : "ɽ",
          "name" : "r hook",
          "title" : "retroflex flap "
        },
        { "html" : "ɺ",
          "inserter" : "ɺ",
          "name" : "r upside long",
          "title" : "alveolar lateral flap "
        }
      ],
    "key" : "R",
    "letter" : "R"
  },
  { "chars" : [ { "html" : "ʃ",
          "inserter" : "ʃ",
          "name" : "sh",
          "title" : "voiceless postalveolar fricative "
        },
        { "html" : "ʂ",
          "inserter" : "ʂ",
          "name" : "s hook",
          "title" : "voiceless retroflex fricative "
        }
      ],
    "key" : "S",
    "letter" : "S"
  },
  { "chars" : [ { "html" : "θ",
          "inserter" : "θ",
          "name" : "theta",
          "title" : "voiceless dental fricative "
        },
        { "html" : "t͡ʃ",
          "inserter" : "t͡ʃ",
          "name" : "tsh",
          "title" : "voiceless postalveolar affricate "
        },
        { "html" : "t͡s",
          "inserter" : "t͡s",
          "name" : "ts",
          "title" : "voiceless alveolar affricate "
        },
        { "html" : "ʈ",
          "inserter" : "ʈ",
          "name" : "t long",
          "title" : "voiceless retroflex plosive "
        }
      ],
    "key" : "T",
    "letter" : "T"
  },
  { "chars" : [ { "html" : "ʊ",
          "inserter" : "ʊ",
          "name" : "u horseshoe",
          "title" : "near-close near-back rounded vowel "
        },
        { "html" : "ʊ̈",
          "inserter" : "ʊ̈",
          "name" : "schwu",
          "title" : "near-close central rounded vowel "
        },
        { "html" : "ʉ",
          "inserter" : "ʉ",
          "name" : "u barred",
          "title" : "close central rounded vowel "
        }
      ],
    "key" : "U",
    "letter" : "U"
  },
  { "chars" : [ { "html" : "ʌ",
          "inserter" : "ʌ",
          "name" : "v upside",
          "title" : "open-mid back unrounded vowel "
        },
        { "html" : "ʋ",
          "inserter" : "ʋ",
          "name" : "v smooth",
          "title" : "labiodental approximant "
        },
        { "html" : "ⱱ",
          "inserter" : "ⱱ",
          "name" : "v hook",
          "title" : "labiodental flap "
        }
      ],
    "key" : "V",
    "letter" : "V"
  },
  { "chars" : [ { "html" : "ʍ",
          "inserter" : "ʍ",
          "name" : "w upside",
          "title" : "voiceless labio-velar approximant "
        },
        { "html" : "ɯ",
          "inserter" : "ɯ",
          "name" : "u extra bowl",
          "title" : "close back unrounded vowel "
        },
        { "html" : "ɰ",
          "inserter" : "ɰ",
          "name" : "u extra bowl hook",
          "title" : "velar approximant "
        }
      ],
    "key" : "W",
    "letter" : "W"
  },
  { "chars" : [ { "html" : "χ",
          "inserter" : "χ",
          "name" : "chi",
          "title" : "voiceless uvular fricative "
        } ],
    "key" : "X",
    "letter" : "X"
  },
  { "chars" : [ { "html" : "ʎ",
          "inserter" : "ʎ",
          "name" : "y upside",
          "title" : "palatal lateral approximant "
        },
        { "html" : "ɣ",
          "inserter" : "ɣ",
          "name" : "gamma",
          "title" : "voiced velar fricative "
        },
        { "html" : "ʏ",
          "inserter" : "ʏ",
          "name" : "Y small",
          "title" : "near-close near-front rounded vowel "
        },
        { "html" : "ɤ",
          "inserter" : "ɤ",
          "name" : "rams horns",
          "title" : "close-mid back unrounded vowel "
        }
      ],
    "key" : "Y",
    "letter" : "Y"
  },
  { "chars" : [ { "html" : "ʒ",
          "inserter" : "ʒ",
          "name" : "zh",
          "title" : "voiced postalveolar fricative "
        },
        { "html" : "ʐ",
          "inserter" : "ʐ",
          "name" : "z hook",
          "title" : "voiced retroflex fricative "
        },
        { "html" : "ʑ",
          "inserter" : "ʑ",
          "name" : "z loop",
          "title" : "voiced alveolo-palatal fricative "
        }
      ],
    "key" : "Z",
    "letter" : "Z"
  },
  { "chars" : [ { "html" : "ʔ",
          "inserter" : "ʔ",
          "name" : "glottal stop",
          "title" : "glottal stop "
        },
        { "html" : "ʕ",
          "inserter" : "ʕ",
          "name" : "glottal stop flipped",
          "title" : "voiced pharyngeal fricative "
        },
        { "html" : "ʢ",
          "inserter" : "ʢ",
          "name" : "glottal stop flipped barred",
          "title" : "voiced epiglottal fricative "
        },
        { "html" : "ʡ",
          "inserter" : "ʡ",
          "name" : "glottal stop barred",
          "title" : "epiglottal plosive "
        }
      ],
    "key" : "2",
    "letter" : "2"
  },
  { "chars" : [ { "html" : "|",
          "inserter" : "|",
          "name" : "minor group",
          "title" : "minor group "
        },
        { "html" : "‖",
          "inserter" : "‖",
          "name" : "major group",
          "title" : "major group "
        }
      ],
    "key" : "1",
    "letter" : "1"
  },
  { "chars" : [ { "html" : "ˈ",
          "inserter" : "ˈ",
          "name" : "primary stress",
          "title" : "primary stress "
        },
        { "html" : "ˌ",
          "inserter" : "ˌ",
          "name" : "secondary stress",
          "title" : "secondary stress "
        }
      ],
    "key" : ",",
    "letter" : ","
  },
  { "chars" : [ { "html" : "ː",
          "inserter" : "ː",
          "name" : "length mark",
          "title" : "length mark "
        },
        { "html" : "ˑ",
          "inserter" : "ˑ",
          "name" : "half length mark",
          "title" : "half-long "
        },
        { "html" : " ̆",
          "inserter" : "̆",
          "name" : "extra short",
          "title" : "extra short "
        }
      ],
    "key" : ".",
    "letter" : "."
  },
  { "chars" : [ { "html" : " ͡ ",
          "inserter" : "͡",
          "name" : "tie bar upper",
          "title" : "tie bar "
        },
        { "html" : " ͜ ",
          "inserter" : "͜",
          "name" : "tie bar lower",
          "title" : "tie bar "
        },
        { "html" : "‿",
          "inserter" : "‿",
          "name" : "linking",
          "title" : "linking "
        }
      ],
    "key" : " ",
    "letter" : "␣"
  },
  { "chars" : [ { "html" : "∅",
          "inserter" : "∅",
          "name" : "zero",
          "title" : "zero "
        },
        { "html" : "→",
          "inserter" : "→",
          "name" : "becomes",
          "title" : "becomes "
        },
        { "html" : " ̈",
          "inserter" : "̈",
          "name" : "centralized",
          "title" : "centralized "
        },
        { "html" : " ̃",
          "inserter" : "̃",
          "name" : "nasalized",
          "title" : "nasalized "
        },
        { "html" : " ̥",
          "inserter" : "̥",
          "name" : "voiceless",
          "title" : "voiceless "
        },
        { "html" : " ̊",
          "inserter" : "̊",
          "name" : "voiceless high",
          "title" : "voiceless "
        },
        { "html" : " ̬",
          "inserter" : "̬",
          "name" : "voiced",
          "title" : "voiced "
        },
        { "html" : " ̩",
          "inserter" : "̩",
          "name" : "syllabic",
          "title" : "syllabic "
        },
        { "html" : "ᵊ",
          "inserter" : "ᵊ",
          "name" : "schwa superscript",
          "title" : "syllabic or schwa "
        },
        { "html" : "ʳ",
          "inserter" : "ʳ",
          "name" : "r superscript",
          "title" : "optional r "
        },
        { "html" : " ˞",
          "inserter" : "˞",
          "name" : "rhotacized",
          "title" : "rhotacized "
        },
        { "html" : "ʰ",
          "inserter" : "ʰ",
          "name" : "aspirated",
          "title" : "aspirated "
        },
        { "html" : "ʷ",
          "inserter" : "ʷ",
          "name" : "labialized",
          "title" : "labialized "
        },
        { "html" : "ʲ",
          "inserter" : "ʲ",
          "name" : "palatalized",
          "title" : "palatalized "
        },
        { "html" : " ̝",
          "inserter" : "̝",
          "name" : "raised",
          "title" : "raised "
        },
        { "html" : " ̞",
          "inserter" : "̞",
          "name" : "lowered",
          "title" : "lowered "
        },
        { "html" : " ̟",
          "inserter" : "̟",
          "name" : "advanced",
          "title" : "advanced "
        },
        { "html" : " ̠",
          "inserter" : "̠",
          "name" : "retracted",
          "title" : "retracted "
        }
      ] },
  { "chars" : [ { "html" : " ̋",
          "inserter" : "̋",
          "name" : "extra high",
          "title" : "extra high"
        },
        { "html" : " ́",
          "inserter" : "́",
          "name" : "high",
          "title" : "high"
        },
        { "html" : " ̄",
          "inserter" : "̄",
          "name" : "mid",
          "title" : "mid"
        },
        { "html" : " ̀",
          "inserter" : "̀",
          "name" : "low",
          "title" : "low"
        },
        { "html" : " ̏",
          "inserter" : "̏",
          "name" : "extra low",
          "title" : "extra low"
        },
        { "html" : " ̌",
          "inserter" : "̌",
          "name" : "rising",
          "title" : "rising"
        },
        { "html" : " ̂",
          "inserter" : "̂",
          "name" : "falling",
          "title" : "falling"
        },
        { "html" : " ᷄",
          "inserter" : "᷄",
          "name" : "high rising",
          "title" : "high rising"
        },
        { "html" : " ᷅",
          "inserter" : "᷅",
          "name" : "low rising",
          "title" : "low rising"
        },
        { "html" : " ᷈",
          "inserter" : "᷈",
          "name" : "rising-falling",
          "title" : "rising-falling"
        }
      ] },
  { "chars" : [ { "html" : "ʼ",
          "inserter" : "ʼ",
          "name" : "ejective",
          "title" : "ejective "
        },
        { "html" : " ̪",
          "inserter" : "̪",
          "name" : "dental",
          "title" : "dental "
        },
        { "html" : " ̺",
          "inserter" : "̺",
          "name" : "apical",
          "title" : "apical "
        },
        { "html" : " ̹",
          "inserter" : "̹",
          "name" : "more rounded",
          "title" : "more rounded "
        },
        { "html" : " ̜",
          "inserter" : "̜",
          "name" : "less rounded",
          "title" : "less rounded "
        },
        { "html" : " ̽",
          "inserter" : "̽",
          "name" : "mid-centralized",
          "title" : "mid-centralized "
        },
        { "html" : " ̯",
          "inserter" : "̯",
          "name" : "non-syllabic",
          "title" : "non-syllabic "
        },
        { "html" : " ̤",
          "inserter" : "̤",
          "name" : "breathy voiced",
          "title" : "breathy voiced"
        },
        { "html" : " ̰",
          "inserter" : "̰",
          "name" : "creaky voiced",
          "title" : "creaky voiced"
        },
        { "html" : " ̼",
          "inserter" : "̼",
          "name" : "linguolabial",
          "title" : "linguolabial"
        },
        { "html" : "ˠ",
          "inserter" : "ˠ",
          "name" : "velarized",
          "title" : "velarized "
        },
        { "html" : "ˤ",
          "inserter" : "ˤ",
          "name" : "pharyngealized",
          "title" : "pharyngealized "
        },
        { "html" : " ̴",
          "inserter" : "̴",
          "name" : "velarized or pharyngealized",
          "title" : "velarized or pharyngealized "
        },
        { "html" : "ⁿ",
          "inserter" : "ⁿ",
          "name" : "nasal release",
          "title" : "nasal release "
        },
        { "html" : "ˡ",
          "inserter" : "ˡ",
          "name" : "lateral release",
          "title" : "lateral release "
        },
        { "html" : "ʱ",
          "inserter" : "ʱ",
          "name" : "breathy-voice aspirated",
          "title" : "breathy-voice aspirated "
        },
        { "html" : " ̘",
          "inserter" : "̘",
          "name" : "advanced tongue root",
          "title" : "advanced tongue root "
        },
        { "html" : " ̙",
          "inserter" : "̙",
          "name" : "retracted tongue root",
          "title" : "retracted tongue root "
        },
        { "html" : " ̻",
          "inserter" : "̻",
          "name" : "laminal",
          "title" : "laminal"
        },
        { "html" : "̚",
          "inserter" : "̚",
          "name" : "not audibly released",
          "title" : "not audibly released"
        }
      ] },
  { "chars" : [ { "html" : "˥",
          "inserter" : "˥",
          "name" : "extra high alt",
          "title" : "extra high"
        },
        { "html" : "˦",
          "inserter" : "˦",
          "name" : "high alt",
          "title" : "high"
        },
        { "html" : "˧",
          "inserter" : "˧",
          "name" : "mid alt",
          "title" : "mid"
        },
        { "html" : "˨",
          "inserter" : "˨",
          "name" : "low alt",
          "title" : "low"
        },
        { "html" : "˩",
          "inserter" : "˩",
          "name" : "extra low alt",
          "title" : "extra low"
        },
        { "html" : "˩˥",
          "inserter" : "˩˥",
          "name" : "rising alt",
          "title" : "rising"
        },
        { "html" : "˥˩",
          "inserter" : "˥˩",
          "name" : "falling alt",
          "title" : "falling"
        },
        { "html" : "˦˥",
          "inserter" : "˦˥",
          "name" : "high rising alt",
          "title" : "high rising"
        },
        { "html" : "˩˨",
          "inserter" : "˩˨",
          "name" : "low rising alt",
          "title" : "low rising"
        },
        { "html" : "˧˦˧",
          "inserter" : "˧˦˧",
          "name" : "rising-falling alt",
          "title" : "rising-falling"
        },
        { "html" : "↓",
          "inserter" : "↓",
          "name" : "downstep",
          "title" : "downstep"
        },
        { "html" : "↑",
          "inserter" : "↑",
          "name" : "upstep",
          "title" : "upstep"
        },
        { "html" : "↗",
          "inserter" : "↗",
          "name" : "global rise",
          "title" : "global rise"
        },
        { "html" : "↘",
          "inserter" : "↘",
          "name" : "global fall",
          "title" : "global fall"
        }
      ] },
  { "chars" : [ { "html" : "ʘ",
          "inserter" : "ʘ",
          "name" : "bilabial click",
          "title" : "bilabial click"
        },
        { "html" : "ǀ",
          "inserter" : "ǀ",
          "name" : "dental click",
          "title" : "dental click"
        },
        { "html" : "ǃ",
          "inserter" : "ǃ",
          "name" : "retroflex click",
          "title" : "retroflex click"
        },
        { "html" : "ǂ",
          "inserter" : "ǂ",
          "name" : "palatoalveolar click",
          "title" : "palatoalveolar click"
        },
        { "html" : "ǁ",
          "inserter" : "ǁ",
          "name" : "alveolar lateral click",
          "title" : "alveolar lateral click"
        }
      ] }
];
IPAKeyboard.indices={"1":25,"2":24,"3":5,"A":0,"B":1,"C":2,"D":3,"E":4,"G":6,"H":7,"I":8,"J":9,"L":10,"M":11,"N":12,"O":13,"P":14,"R":15,"S":16,"T":17,"U":18,"V":19,"W":20,"X":21,"Y":22,"Z":23,",":26,".":27," ":28};
IPAKeyboard.addButton=function(text,callback,identify,pic,tooltip,force){
//Callback must take id of textarea as argument.
force = typeof force !== 'undefined' ? force : false;
var tas=force?$('.wmd-container'):$('.wmd-container').not(".canhasbutton"+identify);
$.each(tas,function(){
try{
if($(this).find("[id^=wmd-button-row]").length==0){
	setTimeout(function(){IPAKeyboard.addButton(text,callback,identify,pic,true)},100);
	return;
}else{

	this.className+=" canhasbutton"+identify
}
tid=$(this).find("[id^=wmd-input]")[0].id;
row=$(this).find("[id^=wmd-button-row]")[0];
lastel=$(row).find(".wmd-button").not(".wmd-help-button").filter(":last");
if(lastel.length>0){
px=parseInt(lastel[0].style.left.replace("px",""))+25;
//add code for background-position of span as well later
btn='<li class="wmd-button" style="left: '+px+'px; "><span style="background-image:url('+pic+');text-align:center;">'+text+'</span></li>';

$(btn).on("click",function(){callback(tid)}).attr("title",tooltip).insertAfter(lastel);

btn=$(row).find(".wmd-button").not(".wmd-help-button").filter(":last");
if(pic==""){
$(btn).children('span').hover(function(){$(this).css('background','#DEEDFF')},function(){$(this).css('background','none')});
}
}

}catch(e){console.log(e)}
})
}

IPAKeyboard.clickButtonEventLambda=function(left, right,tid){
 return function () {

	 	node=$('#'+tid)[0];

		try{
        //--- Wrap selected text or insert at curser.
        var oldText         = node.value || node.textContent;
        var newText;
        var iTargetStart    = node.selectionStart;
        var iTargetEnd      = node.selectionEnd;

        if (iTargetStart == iTargetEnd)
            newText         = left+right;
        else
            newText         = left + oldText.slice (iTargetStart, iTargetEnd) + right;

        //console.log (newText);
        newText             = oldText.slice (0, iTargetStart) + newText + oldText.slice (iTargetEnd);
        node.value          = newText;
        //-- After using spelling corrector, this gets buggered, hence the multiple sets.
        node.textContent    = newText;
        //-- Have to reset selection, since we repasted the text.
        node.selectionStart = iTargetStart + left.length+1;
        node.selectionEnd   = iTargetEnd   + left.length+right.length;
			//node.blur();
            node.focus();
        try {
            //--- This is a utility function that SE currently provides on its pages.

            StackExchange.MarkdownEditor.refreshAllPreviews ();
            setTimeout(StackExchange.MarkdownEditor.refreshAllPreviews,10);
        }
    
        catch (e) {
            console.warn ("***Userscript error: refreshAllPreviews() is no longer defined!");
        }
		}catch (e) {
            console.warn ("***Textarea does not exist");
			console.log(e);
        }
		return false;
    }


}
 IPAKeyboard.makeFloat=function(tid){

     if($('#IPA-'+tid).length>0){
         return;
     }
     var floatdiv=$("<div id=IPA-"+tid+" class=IPAHolder><div class=handle style='background-color:grey;'></div><div class=ipakeybd></div></div>").css({"z-index":5,position:"absolute",left:"665px","background-color":"white"})
     var ctr=0;
     $('#content').css('overflow','visible')
     floatdiv.insertBefore('#'+tid);
     for(var i=0;i<IPAKeyboard.data.length;i++){
         if(ctr>16){
         ctr=0;
         $('<BR>').appendTo($(floatdiv).find('.ipakeybd'));     
        }
         var el=$('<kbd></kbd>')
         el.appendTo($(floatdiv).find('.ipakeybd'));
         var keyset=IPAKeyboard.data[i];
         if(keyset.letter){
             $('<span class=IPALabel>'+keyset.letter+'</span>').appendTo(el);
         }
         var rpt=(keyset.key==" ")?"<Space>":keyset.key;
         for(var j=0;j<keyset.chars.length;j++){
             
             el2=$('<span class=IPAKey name="'+keyset.chars[j].name+'" title="'+keyset.chars[j].name+(keyset.key?' (Ctrl+'+rpt+')':'')+'">'+keyset.chars[j].html+'</span>').on('click',IPAKeyboard.clickButtonEventLambda("",keyset.chars[j].inserter,tid));
             el2.appendTo(el);
             rpt=rpt+((keyset.key==" ")?"<Space>":keyset.key);
             ctr++;
         }
        
        
        
        
     }
     var closebox=$('<a href="javascript:void(0)">X</a>').css({border:"1px solid black","color":"red","font-weight":"bold"}).on("click",function(){$('#IPA-'+tid).remove();return false;});
     closebox.appendTo(floatdiv.find('.handle'))
    floatdiv.find('kbd:not(:has(.IPALabel))').children().css({"font-size":"14px","padding-left":"2px","padding-right":"2px"});
     
     return false;
 };   
        

//with_jquery(function($) {
 IPAKeyboard.addButton('<span style="font-size:16px">ə</span>',  IPAKeyboard.makeFloat ,'ipakeyboard','','Open IPA Keyboard',false)
//});
  IPAKeyboard.backspaceAtCursor=function (tid)
  {
    var field = document.getElementById(tid);

    if(field.selectionStart)
    {
      var startPos = field.selectionStart;
      var endPos = field.selectionEnd;

      if(field.selectionStart == field.selectionEnd)
      {
        field.value = field.value.substring(0, startPos - 1) + field.value.substring(endPos, field.value.length);

        field.focus(); 
        field.setSelectionRange(startPos - 1, startPos - 1); 
      }
      else
      {
        field.value = field.value.substring(0, startPos) + field.value.substring(endPos, field.value.length);

        field.focus(); 
        field.setSelectionRange(startPos, startPos); 
      }
    }
  }
  $("textarea.wmd-input").live('keydown',function(e){
      if($('#IPA-'+this.id).length==0){return true;}

      if(e.keyCode==18){
        $(this).data('altdown',true);
        $(this).data('keytimes',0);
        return false;
      }else{
         //debugger;
          if($(this).data('altdown')==false||!e.altKey){return true;}
          var ltr=String.fromCharCode(e.keyCode).toUpperCase();
          console.log([ltr,e,IPAKeyboard]);
          if(IPAKeyboard.indices[ltr]||IPAKeyboard.indices[ltr]===0){
              
              var datablock=IPAKeyboard.data[IPAKeyboard.indices[ltr]];

              if($(this).data('keytimes')==0){
                  $(this).data('keychar',ltr)
                 $(this).data('keytimes',1);

                 IPAKeyboard.clickButtonEventLambda("",datablock.chars[0].inserter,this.id)();
         
              }else{
                  
                  if( $(this).data('keychar')!=ltr){  
                        for(var i=0;i<datablock.chars[($(this).data('keytimes')-1)%datablock.chars.length].inserter.length;i++){
                  IPAKeyboard.backspaceAtCursor(this.id);
                    }
                       $(this).data('altdown',true);
                        $(this).data('keychar',ltr)
                        $(this).data('keytimes',1);
                            IPAKeyboard.clickButtonEventLambda("",datablock.chars[0].inserter,this.id)();            
                    }
                    for(var i=0;i<datablock.chars[($(this).data('keytimes')-1)%datablock.chars.length].inserter.length;i++){
                  IPAKeyboard.backspaceAtCursor(this.id);
              }
                  $(this).data('keytimes',$(this).data('keytimes')+1);
                  IPAKeyboard.clickButtonEventLambda("",datablock.chars[($(this).data('keytimes')-1)%datablock.chars.length].inserter,this.id)();
              }
          
            return false;
          }
          
    }
    
    });
    
     $("textarea.wmd-input").live('keyup',function(e){
               if($('#IPA-'+this.id).length==0){return true;}
      if(e.which==18){
          console.log(['keyup',e])
        $(this).data('altdown',false);
        $(this).data('keytimes',0);
        return false;
      }
      
  });
