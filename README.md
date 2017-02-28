# Upload Field Type

*anomaly.field_type.upload*

#### A file upload field type.

The file file type provides a file input that uploads to the Files module.

#### Example

Using the contact form plugin:

```
{{ form('contact').fields({
	'name': {
		'required': true,
		'label': 'REsume',
		'type': 'anomaly.field_type.upload',
		'config' : {
			'folder':'uploads'
		}
	}
})|raw }}
```
