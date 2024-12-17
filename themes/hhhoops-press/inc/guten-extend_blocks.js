/**
 * External dependencies
 */
const { __ } = wp.i18n;
const { createBlock } = wp.blocks;
const { registerBlockType } = wp.blocks;
const { without } = lodash;

// import { __ } from '@wordpress/i18n';
// import { createBlock, registerBlockType } from '@wordpress/blocks';
// import { without } from 'lodash';

/**
 * Internal dependencies
 */

import '../../../plugins/woocommerce/packages/woocommerce-blocks/assets/js/blocks/product-category/editor.scss';
import Block from './blocks/product-category-hhhoops/block';
import { deprecatedConvertToShortcode } from '../../../plugins/woocommerce/packages/woocommerce-blocks/assets/js/utils/deprecations';
import sharedAttributes, {
	sharedAttributeBlockTypes,
} from '../../../plugins/woocommerce/packages/woocommerce-blocks/assets/js/utils/shared-attributes';

/**
 * Register and run the "Products by Category" block.
 */
wp.blocks.registerBlockType( 'woocommerce/product-category-hhhoops', {
	title: __( 'hhhoops Products by Category', 'woocommerce' ),
	icon: {
		src: 'category',
		foreground: '#96588a',
	},
	category: 'woocommerce',
	keywords: [ __( 'WooCommerce', 'woocommerce' ) ],
	description: __(
		'hhhoops Display a grid of products from your selected categories.',
		'woocommerce'
	),
	supports: {
		align: [ 'wide', 'full' ],
		html: false,
	},
	example: {
		attributes: {
			isPreview: true,
		},
	},
	attributes: {
		...sharedAttributes,

		/**
		 * Toggle for edit mode in the block preview.
		 */
		editMode: {
			type: 'boolean',
			default: true,
		},

		/**
		 * How to order the products: 'date', 'popularity', 'price_asc', 'price_desc' 'rating', 'title'.
		 */
		orderby: {
			type: 'string',
			default: 'date',
		},
	},

	transforms: {
		from: [
			{
				type: 'block',
				blocks: without(
					sharedAttributeBlockTypes,
					'woocommerce/product-category-hhhoops'
				),
				transform: ( attributes ) =>
					wp.blocks.createBlock( 'woocommerce/product-category-hhhoops', {
						...attributes,
						editMode: false,
					} ),
			},
		],
	},

	deprecated: [
		{
			// Deprecate shortcode save method in favor of dynamic rendering.
			attributes: {
				...sharedAttributes,
				editMode: {
					type: 'boolean',
					default: true,
				},
				orderby: {
					type: 'string',
					default: 'date',
				},
			},
			save: deprecatedConvertToShortcode(
				'woocommerce/product-category-hhhoops'
			),
		},
	],

	/**
	 * Renders and manages the block.
	 */
	edit( props ) {
		return <Block { ...props } />;
	},

	save() {
		return null;
	},
} );
