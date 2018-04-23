# processor-filter-files

[![Build Status](https://travis-ci.org/keboola/processor-filter-files.svg?branch=master)](https://travis-ci.org/keboola/processor-filter-files)

Filters only files matching a given mask. Files are filtered recursively, manifest files are kept.

# Usage

## Parameters

Processor supports these mandatory parameters:

 - `mask` -- Either a filename or a glob pattern.

## Sample configurations

### Simple mask

```
{
    "definition": {
        "component": "keboola.processor-filter-files"
    },
    "parameters": {
        "mask": "myfile.csv"
    }
}

```

Source folder structure

```
/data/in/tables/myfile.csv
/data/in/tables/myfile.csv.manifest
/data/in/tables/slice/myfile.csv
/data/in/tables/slice.manifest
/data/in/tables/someothermyfile.csv
```

Result structure

```
/data/out/tables/myfile.csv
/data/out/tables/myfile.csv.manifest
/data/out/tables/slice/myfile.csv
/data/out/tables/slice.manifest

```

### Sliced tables

Manifests will be transferred also for sliced tables, eg.

{
    "definition": {
        "component": "keboola.processor-filter-files"
    },
    "parameters": {
        "mask": "slice1"
    }
}

Source folder structure

```
/data/in/tables/slice/slice1
/data/in/tables/slice/slice2
/data/in/tables/slice.manifest
```

Result structure

```
/data/out/tables/slice/slice1
/data/out/tables/slice.manifest

```


## Development

Clone this repository and init the workspace with following command:

```
git clone https://github.com/keboola/processor-filter-files
cd processor-filter-files
docker-compose build
docker-compose run --rm dev composer install --no-scripts
```

Run the test suite using this command:

```
docker-compose run --rm dev composer ci
```

# Integration

For information about deployment and integration with KBC, please refer to the [deployment section of developers documentation](https://developers.keboola.com/extend/component/deployment/)
