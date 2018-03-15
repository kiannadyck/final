# final - Flashcard Website

This website separates all database and business logic using the MVC pattern.
Objects/classes are in in their own directory, as are all all other components. The index page is the controller that creates and manages the routes.
Database interactions are handled by PHP in the models folder.
All database interactions are handled by function calls, which utilize PDO.
HTML pages contain content and are accessed via routes managed by the index page.

The Controller routes all URLs and uses the Fat Free Framework to display information stored in the hive onto HTML pages.

The user is allowed to access the flashcard tool after they have login credentials, 
created via email and password sign-up. Emails may not be duplicates, and ensures there is one account per email address.
The user may create new flashcards, or update, edit, and delete existing decks.

The decks are questionAnswer Objects. Each time a user creates a new deck, it creates a questionAnswer object, 
which inherits the name property from its super, Deck.

All php files in the models directory contain DocBlocks that describe the file and individual functions.

A bulk of the work was done pair programming. We tried to switch off laptops, to reflect a balance in git commits.

//validation

//jquery and ajax
