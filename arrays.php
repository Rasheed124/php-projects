

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website <?php ?></title>
</head>
<body>

<style>
    pre{
        background-color: rgba(201, 201, 201, 0.8);
        padding: 40px;
        width: 600px;
        margin:  0 auto;
        font-size: 15px;
        border: 1px solid black;
}
</style>
<pre>



    <?php

        $books = [
            'Harry Potter'        => 'J.K. Rowling',
            'Lord of the Rings'   => 'J.R.R. Tolkien',
            'The Little Prince'   => 'Antoine de Saint-Exupéry',
            'Don Quixote'         => 'Miguel de Cervantes',
            'Alice in Wonderland' => 'Lewis Carroll',
            'another book'
        ];

        // var_dump(array_key_exists('The Little Prince', $books));

        // foreach (array_keys($books) as $author) {
        //     var_dump($author);
        // }
        foreach (array_values($books) as $book) {
            var_dump($book);
        }

        // $authors = [
        //     'J.K. Rowling',
        //     'J.R.R. Tolkien',
        //     'Antoine de Saint-Exupéry',
        //     'Miguel de Cervantes',
        //     'Lewis Carroll'
        // ];

        // var_dump("{$books[0]} has been written by {$authors[0]}.");

        // var_dump("{$books[0]} has been written by {$authors[0]}.");

    ?>

</pre>



</body>
</html>
