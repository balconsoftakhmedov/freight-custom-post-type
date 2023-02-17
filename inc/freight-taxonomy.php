<?php
/**
 *
 * @author    balconet.co
 * @package   Tigon
 * @version   1.0.0
 * @since     1.0.0
 */

function create_freight_taxonomy() {
	register_taxonomy(
			'freight_types',
			'freight',
			array(
					'labels'        => array(
							'name'          => 'Freight Types',
							'add_new_item'  => 'Add New Freight Type',
							'new_item_name' => "New Freight Type"
					),
					'show_ui'       => true,
					'show_tagcloud' => false,
					'hierarchical'  => true,
			)
	);
}

add_action( 'init', 'create_freight_taxonomy' );
function add_freight_types_image_field() {
	echo '<div class="form-field term-group">
    <label for="freight_types_image">Image</label>
    <input type="text" id="freight_types_image" name="freight_types_image" value="">
    <p><a href="#" class="upload_image_button button">Upload/Add image</a></p>
  </div>';
}

add_action( 'freight_types_add_form_fields', 'add_freight_types_image_field', 10, 2 );
function edit_freight_types_image_field( $term, $taxonomy ) {
	$image_id  = get_term_meta( $term->term_id, 'freight_types_image', true );
	$image_url = wp_get_attachment_url( $image_id );
	echo '<tr class="form-field term-group-wrap">
    <th scope="row"><label for="freight_types_image">Image</label></th>
    <td><input type="text" id="freight_types_image" name="freight_types_image" value="' . esc_url( $image_url ) . '">
    <p><a href="#" class="upload_image_button button">Upload/Add image</a></p>
    </td>
  </tr>';
}

add_action( 'freight_types_edit_form_fields', 'edit_freight_types_image_field', 10, 2 );
function save_freight_types_image( $term_id, $tt_id ) {
	if ( isset( $_POST['freight_types_image'] ) && '' !== $_POST['freight_types_image'] ) {
		$image_url = $_POST['freight_types_image'];
		$image_id  = attachment_url_to_postid( $image_url );
		update_term_meta( $term_id, 'freight_types_image', $image_id );
	}
}

add_action( 'created_freight_types', 'save_freight_types_image', 10, 2 );
add_action( 'edited_freight_types', 'save_freight_types_image', 10, 2 );
function load_wp_media_files() {
	wp_enqueue_media();
}

add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
function add_script_to_admin_footer() {
	?>
	<script>
		jQuery(document).ready(function ($) {
			$(".upload_image_button").click(function (e) {
				e.preventDefault();
				var custom_uploader = wp.media({
					title: "Insert Image",
					button: {
						text: "Use this image"
					},
					multiple: false
				}).on("select", function () {
					var attachment = custom_uploader.state().get("selection").first().toJSON();
					$("#freight_types_image").val(attachment.url);
				}).open();
			});
		});
	</script>
	<?php
}

add_action( 'admin_footer', 'add_script_to_admin_footer' );
function show_freight_types_image( $term, $taxonomy ) {
	$image_id  = get_term_meta( $term->term_id, 'freight_types_image', true );
	$image_url = wp_get_attachment_url( $image_id );
	if ( $image_url ) {
		echo '<img src="' . esc_url( $image_url ) . '" alt="">';
	}
}

add_action( 'freight_types_edit_form_fields', 'show_freight_types_image', 10, 2 );
function create_freight_categories() {
	$categories = [
			"Домашние вещи"             => [
					"Мебель",
					"Бытовая техника",
					"Коробки",
					"Растения",
					"Тренажеры",
					"Сейф",
					"Бильярд",
					"Пианино / Рояль",
					"Игровые автоматы",
					"Хрупкие грузы",
					"Контейнер",
					"Личные вещи",
					"Сантехника",
					"Другое"
			],
			"Попутные грузы"            => [
					"Запаллеченный груз",
					"Оборудование",
					"Инструменты",
					"Стройматериалы",
					"Отделочные материалы",
					"Запчасти",
					"Мототехника",
					"Животные",
					"Мебель",
					"Бытовая техника",
					"Коробки",
					"Личные вещи",
					"Другое"
			],
			"Заказать отдельную машину" => [
					"Портер, Соболь (до 1т.)",
					"Газель (аналоги, до 1,5 т.)",
					"Бычок (аналоги, до 3 т.)",
					"5-ти тонник",
					"10-ти тонник",
					"20-ти тонник",
					"Спец. транспорт",
					"Каблук (до 650 кг.)",
					"Другое"
			],
			"Строительные грузы"        => array(
					"Запаллеченный груз",
					"Химия",
					"Лесоматериалы",
					"Биг-Бег (Big-Bag)",
					"Песок",
					"Отделочные материалы",
					"Железобетонные изделия",
					"Оборудование",
					"Инструменты",
					"Лакокрасочные изделия",
					"Металл",
					"Груз навалом",
					"Стройматериалы",
					"Щебень",
					"Другое"
			),
			"Коммерческие грузы"        => array(
					"ТНП",
					"Двери",
					"Стеклянные изделия",
					"Пластик",
					"Тара и упаковка",
					"Запаллеченный груз",
					"Макулатура",
					"Биг-Бег (Big-Bag)",
					"Лом",
					"Химия",
					"Оборудование",
					"Инструменты",
					"Стройматериалы",
					"Отделочные материалы",
					"РТИ",
					"Запчасти",
					"Контейнер",
					"Другое"
			),
			"Перевозка животных"        => array(
					"Домашние животные",
					"Сельскохозяйственные животные",
					"Другое"
			),
			'Пассажирские перевозки'    => [
					'Междугородняя перевозка',
					'Перевозка по городу',
					'Другое'
			],
			'Продукты питания'          => [
					'Алкоголь',
					'Безалкогольные напитки',
					'Крупы',
					'Мороженные и охлажденные продукты',
					'Овощи',
					'Фрукты',
					'Мясные изделия',
					'Рыба',
					'Консервы',
					'Другое'
			],
			'Транспорт и запчасти'      => [
					'Мототехника',
					'Водный транспорт',
					'Запчасти',
					'Другие ТС и прицепы',
					'Перевозка автобусов',
					'Перевозка грузовиков',
					'Квадроцикл',
					'Катер',
					'Лодка',
					'Гидроцикл',
					'Автомобили',
					'Снегоход',
					'Другое'
			],
			'Сыпучие грузы'             => [
					'Торф',
					'Уголь',
					'Песок',
					'Другое'
			]
	];
	foreach ( $categories as $parent_category => $subcategories ) {
		if ( ! term_exists( $parent_category, 'freight_types' ) ) {
			$parent_term = wp_insert_term(
					$parent_category,
					'freight_types',
					array(
							'description' => '',
							'slug'        => sanitize_title( $parent_category )
					)
			);
		} else {
			$parent_term = get_term_by( 'name', $parent_category, 'freight_types' );
		}
		if ( is_array( $subcategories ) ) {
			foreach ( $subcategories as $subcategory ) {
				if ( ! term_exists( $subcategory, 'freight_types' ) ) {
					wp_insert_term(
							$subcategory,
							'freight_types',
							array(
									'description' => '',
									'slug'        => sanitize_title( $subcategory ),
									'parent'      => $parent_term['term_id']
							)
					);
				}
			}
		}
	}
}

add_action( 'init', 'create_freight_categories' );
function get_freight_taxonomy_list() {

	ob_start();
	?>


	<div id="freight-types-wrapper">
		<?php
		$taxonomy = 'freight_types';
		$terms    = get_terms( array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'parent'     => 0
		) );
		foreach ( $terms as $term ) {
			$term_id   = $term->term_id;
			$term_name = $term->name;
			?>
			<button class="freight-type-button"><?php echo $term_name; ?></button>
			<div class="freight-type-subcategories">
				<?php
				$sub_terms = get_terms( array(
						'taxonomy'   => $taxonomy,
						'hide_empty' => false,
						'parent'     => $term_id
				) );
				foreach ( $sub_terms as $sub_term ) {
					$sub_term_id   = $sub_term->term_id;
					$sub_term_name = $sub_term->name;
					?>
					<button class="freight-type-subcategory"><?php echo $sub_term_name; ?></button>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>

	</div>


	<?php
	wp_reset_postdata();
	$output = ob_get_clean();

	return $output;
}

add_shortcode( 'freight_taxonomy_list', 'get_freight_taxonomy_list' );
