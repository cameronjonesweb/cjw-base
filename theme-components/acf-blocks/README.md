# Render ACF Blocks With Template Part

A generic ACF Blocks render callback to handle basic block features.

### Usage

From ACF 5.8.9 the render callback is set automatically, otherwise set the render callback attribute in your `acf_register_block_type` call.

```
acf_register_block_type(
	array(
		...
		'render_callback' => 'WP_Theme_Components\Render_ACF_Blocks_With_Template_Part\render_acf_block',
	)
);
```
