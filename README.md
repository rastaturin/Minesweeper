# Minesweeper console game

This is console version of the minesweeper game written on PHP.

## Getting started

This game requires PHP 5.6 or higher.

```
clone github.com/rastaturin/Minesweeper
cd Minesweeper
php main.php
```

## Use file for input

- Modify `commands.txt` as you like.
- Run `php main.php commands.txt` 

## Example of execution 

```
Enter width
>20
Enter height
>10
Enter bomb amount
>4
   01234567890123456789
 0|OOOOOOOOOOOOOOOOOOOO
 1|OOOOOOOOOOOOOOOOOOOO
 2|OOOOOOOOOOOOOOOOOOOO
 3|OOOOOOOOOOOOOOOOOOOO
 4|OOOOOOOOOOOOOOOOOOOO
 5|OOOOOOOOOOOOOOOOOOOO
 6|OOOOOOOOOOOOOOOOOOOO
 7|OOOOOOOOOOOOOOOOOOOO
 8|OOOOOOOOOOOOOOOOOOOO
 9|OOOOOOOOOOOOOOOOOOOO
Enter action (o - open, m - mark)
>o
Enter X
>1
Enter Y
>1
   01234567890123456789
 0|
 1|
 2|
 3|
 4|
 5|   111        111
 6|   1O1     1111O1
 7|   111     2O2111
 8|           2O2
 9|           1O1
1 remain to open.
Enter action (o - open, m - mark)
>o
Enter X
>12
Enter Y
>9
   01234567890123456789
 0|
 1|
 2|
 3|
 4|
 5|   111        111
 6|   1O1     1111O1
 7|   111     2O2111
 8|           2O2
 9|           111
0 remain to open.
You won!

```