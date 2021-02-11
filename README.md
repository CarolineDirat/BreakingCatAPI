# Breaking Cat Api

![Check-Security](https://github.com/CarolineDirat/BreakingCatAPI/workflows/Check-Security/badge.svg?branch=develop&event=push) ![php-cs-fixer](https://github.com/CarolineDirat/BreakingCatAPI/workflows/php-cs-fixer/badge.svg?branch=develop&event=push) ![phpstan](https://github.com/CarolineDirat/BreakingCatAPI/workflows/phpstan/badge.svg?branch=develop&event=push)

| PHPunit | Codacy |
| ------- | ------ |
|[![pipeline status](https://gitlab.com/Squirrel-Jo/BreakingCatAPI/badges/develop/pipeline.svg)](https://gitlab.com/Squirrel-Jo/BreakingCatAPI/-/commits/develop) [![coverage report](https://gitlab.com/Squirrel-Jo/BreakingCatAPI/badges/develop/coverage.svg)](https://gitlab.com/Squirrel-Jo/BreakingCatAPI/-/commits/develop)|[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8431cca53d5a4b22bf2ffee9e8be848d)](https://www.codacy.com/gh/CarolineDirat/BreakingCatAPI/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=CarolineDirat/BreakingCatAPI&amp;utm_campaign=Badge_Grade) |

![](https://github.com/CarolineDirat/BreakingCatAPI/blob/master/breaking_cat_header.png)
*Image from <https://breakingbad.echosystem.fr/>*

The fun API which put Breaking Bad Quotes in cat photos.

Breaking Bad Quotes comes from [Breaking Bad Quotes API](https://breakingbadquotes.xyz/), and cat photos comes from another API: [Cataas.com](https://cataas.com/#/)

## GET /api/ramdon-jpeg

Will return a **ramdom jpeg image** :

- the picture is a **random cat** from Cataas.com: <https://cataas.com/cat>
- the message under the picture is a **random quote** from the Breaking Bad Quotes API: <https://breaking-bad-quotes.herokuapp.com/v1/quotes>

![](https://github.com/CarolineDirat/BreakingCatAPI/blob/master/ramdom-jpeg.png)
