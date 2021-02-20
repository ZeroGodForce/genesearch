# Gene Search

## Installation

Clone the repo from
```sh
git clone git@github.com:ZeroGodForce/genesearch.git
```

To install, there are no special requirements and no databased used. From within the `genesearch` directory run:

```sh
composer install
```

For the environment file, you may copy or rename the `.env.example` file to `.env`.  However, there is nothing to actually be modified itself directly.  Only the app key needs generating

```sh
php artisan key:generate
```

Finally, The test file is in the root directory, please move it to `storage/app/public/input_tiny.vcf`.


## Usage

You can run the program from the commandline in the folder

```sh
php artisan serve
```

Then open your browser to `http://localhost:8080` (or whatever the cli output tells you if that port isn't available).  To search a for a given allele, in the url bar, enter your terms in the format `/search/{chromosome}/{position}`.  For example: 
```
http://localhost:8080/search/chr1/14023
```

## Questions

### Question 1: What are the limitation/problems with this solution?
- I don't love the multiple foreach loops needed in order to get the data into an easily searchable format
- There is no error handling or exceptions in case of invalid data coming from the .vcf file itself
- It works on numerical indexes alone, and while this may be fine given that the file has a standard format, I would have preferred an associative array
- There is no form to upload .vcf file data, nor a form to enter the parameters in, it is done via url
- No database was used.  Ordinarily I would but for the sake of brevity I resisted the urge to do so
- All the functionality is contained in the controller.  I prefer my controllers light, and would probably have split this out into a service class
- There would be a noticeable decrease in performance even if the dataset was only 10x larger


### Question 2: How would it scale?
Not well to be honest, especially if what I have learned about the usual size of these files being huge performance issues.  
Large datasets like these are best stored in databases with results queried out rather than reading straight from the 
.vcf file itself.  I don't know too much about NoSQL databases, but given this type of data and the (slightly) variable nature of it, it seems 
like a document database would be suited for data like this.  Maybe something like MongoDB.


### Question 3: How would you test it efficiently?
With more time to write tests and a database, I would likely abstract the import, upload, search and serve functions into
a class, and then test each function with a small subset of data (similar in size as the included vcf file).  As it is presently, I would feed it a dataset no larger than 5 times the size of the provided file.

