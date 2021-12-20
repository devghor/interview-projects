# REST Specification
Here we describe how will be your resource document structure.

## TOC
1. [Document Root](#Document Root)

## Document Root
A document MUST contain at least one of the following top-level members:

- **data:**  data is a representation of the resource or collection of resources.
> It can be a object, array of object  or null
```json
{
  "data": {
      
  }
}
```

- **errors:**  an array of error strings

> It can be an array of string or an empty array ([])

```json
{
  "errors": []
}
```
