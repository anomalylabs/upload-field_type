# Upload Field Type

*anomaly.field_type.upload*

#### A file upload field type.

The file file type provides a file input that uploads to the Files module.

#### use with contact form

Don't forget to config folder

```
{{ form('contact').fields({
	'name': {
		'label': 'CV',
		'required': true,
		'type':'anomaly.field_type.upload',
		'config' :{
			'folder':'uploads'
		}
	}
})|raw }}```
