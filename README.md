# silverstripe-api

## Usage

Use `SerializableExtension` to provide a model with the required serialization API.

```
FooPage:
  extensions:
    - SerializableExtension
```

```
FooPage extends Page
{
}

FooPage_Controller extends ApiController
{
}

```

You can specify the transformer or serializer for a particular model using the config:

```
FooPage:
  transformer: MyCustomTransformer
  serializer: MyCustomSerializer
```

## Access control
Access control is implemented through the Member system