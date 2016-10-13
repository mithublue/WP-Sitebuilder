<template id="container-template">
    <div id="{{ cont_id }}" class="container-fluid"
         data-type="{{ type }}" data-parent_id="{{ parent_id }}" data-parent_type="{{ parent_type }}"
    >
        <div href="javascript:" class="btn br0 cover_label wpsb_wrapper_label">Builder
            <span class="btn btn-blue br0 pull-right btn-xs glyphicon glyphicon-plus" @click="dispatch_add_row(cont_id)"> Section </span>
        </div>
        <div>
            <template v-for="row_id in cont_data.child.row">
                <lego_row :row_id="row_id" :row_data="lego_layout.row[row_id]" :lego_layout="lego_layout"
                          :parent_type="'container'" :parent_id="cont_id" :child_type="['col']" :preview_data="preview_data" :type="'row'"
                          :grid_number="grid_number"
                ></lego_row>
            </template>
        </div>
    </div>
</template>
<template id="row-template">
    <div id="{{ row_id }}" class="row row-primary"
         data-type="{{ type }}" data-parent_id="{{ parent_id }}" data-parent_type="{{ parent_type }}"
    >
        <div href="javascript:" class="btn btn-block br0 wpsb_row_label fwb pr">Section
            <div class="pa" style="overflow: hidden;top: 5px;right: 5px;">
                <span class="btn btn-danger br0 pull-right btn-xs glyphicon glyphicon-remove"
                      @click="dispatch_remove({ 'type' : 'row', 'id' : row_id, 'parent_id' : parent_id , 'parent_type' : parent_type, 'child_type' : child_type })"
                ></span>
                <span class="btn btn-blue br0 pull-right btn-xs glyphicon glyphicon-edit edit-row"
                      @click="dispatch_render_row_form( { property : lego_layout.row[row_id].property, type : 'row', id : row_id } )"
                ></span>
                <span class="btn btn-blue br0 pull-right btn-xs glyphicon glyphicon-plus" @click="dispatch_add_placeholder(row_id)"> Placeholder </span>
            </div>
        </div>
        <div style="padding: 10px;">
            <template v-for="col_id in row_data.child.col">
                <lego_col :col_id="col_id" :col_data="lego_layout.col[col_id]" :lego_layout="lego_layout"
                          :parent_type="'row'" :parent_id="row_id" :child_type="['element','row']" :preview_data="preview_data" :type="'col'"
                          :grid_number="grid_number"
                ></lego_col>
            </template>
        </div>
    </div>
</template>
<template id="col-template">
    <div id="{{ col_id }}" class="col-sm-{{ col_data.span }} placeholder-primary lego-placeholder"
         data-type="{{ type }}" data-parent_id="{{ parent_id }}" data-parent_type="{{ parent_type }}"
    >
        <div class="text-center" style="overflow: hidden;margin-left: -15px; margin-right: -15px; position:absolute;top: -25px;">
            <span class="btn btn-danger br0 pull-right btn-xs glyphicon glyphicon-remove"
                  @click="dispatch_remove({ 'type' : 'col', 'id' : col_id, 'parent_id' : parent_id , 'parent_type' : parent_type, 'child_type' : child_type })"
            ></span>
            <span class="btn btn-blue br0 pull-right btn-xs glyphicon glyphicon-edit edit-col"
                  @click="dispatch_render_col_form( { property : lego_layout.col[col_id].property, type : 'col', id : col_id } )"
            ></span>
            <span class="btn btn-success br0 pull-right btn-xs glyphicon glyphicon-plus" @click="popup_widgetslist(col_id)"></span>
        </div>
        <div class="btn br0 cover_label wpsb_placeholder_label pr">
            <?php _e('Placeholder','wpsb');?>
           <div class="pa" style="top: 5px;left: 5px;">
                <span class="btn btn-aqua br0 pull-left btn-xs glyphicon glyphicon-chevron-left"
                      @click="grid_shorten({ 'type' : 'col', 'id' : col_id })"
                ></span>
               <span class="btn btn-aqua br0 pull-left btn-xs glyphicon glyphicon-chevron-right"
                     @click="grid_enlarge({ 'type' : 'col', 'id' : col_id })"
               ></span>
               <span class="btn btn-aqua br0 pull-left btn-xs glyphicon pr"
                     @click="show_grid_number_panel = true"
               ><?php _e('Resize','wpsb'); ?>
            </span>
               <div class="wpsb-grid_number_panel pa" v-show="show_grid_number_panel == true">
                   <span @click="show_grid_number_panel = false" class="btn-red"><?php _e('Cancel','wpsb'); ?></span>
                   <span @click="change_grid_width({ 'type' : 'col', 'id' : col_id, 'span' : i })" v-for="i in grid_array">{{ i }}</span>
               </div>
               <span class="btn btn-blue br0 pull-left btn-xs glyphicon add-builder"
                     @click="dispatch_add_section_in_placeholder( { type : 'col', id : col_id } )"
               ><?php _e('Divide','wpsb'); ?></span>
           </div>
        </div>
        <div v-if="lego_layout.col[col_id].child.element.length == 0 && lego_layout.col[col_id].child.row.length == 0" class="text-center p10"><?php _e('Add elements here','wpsb');?></div>
        <template v-for="elem_id in col_data.child.element">
            <lego_element :elem_id="elem_id" :elem_data="lego_layout.element[elem_id]" :lego_layout="lego_layout"
            :parent_type="'col'" :parent_id="col_id" :child_type="[]" :preview_data="preview_data" :type="'element'"
            ></lego_element>
        </template>
        <template v-for="row_id in col_data.child.row">
            <lego_row :row_id="row_id" :row_data="lego_layout.row[row_id]" :lego_layout="lego_layout"
                      :parent_type="'col'" :parent_id="col_id" :child_type="['col']" :preview_data="preview_data" :type="'row'"
                      :grid_number="grid_number"
            ></lego_row>
        </template>
    </div>
</template>
<template id="element-template">
<div class="elem-primary mt5 lego-elem" id="{{ elem_id }}"
     data-type="{{ type }}" data-parent_id="{{ parent_id }}" data-parent_type="{{ parent_type }}"
>
    <div href="javascript:" class="btn btn-block br0 wpsb_elem_label">
        {{ elem_data.label }}
        <span class="btn btn-danger br0 pull-right btn-xs glyphicon glyphicon-remove"
        @click="dispatch_remove({ 'type' : 'element', 'id' : elem_id, 'parent_id' : parent_id , 'parent_type' : parent_type, 'child_type' : child_type })"
        ></span>
        <span class="btn btn-blue br0 pull-right btn-xs glyphicon glyphicon-edit edit-element" data-label="{{ elem_data.label }}" data-type="{{ elem_data.type }}" data-name="{{ elem_data.name }}" data-formdata="{{ elem_data.instance }}"
              @click="dispatch_render_element_form( elem_id, elem_data.label, elem_data.type, elem_data.name, elem_data.instance, elem_data.id_base  )"
        ></span>
        <span class="btn btn-blue br0 pull-right btn-xs  glyphicon glyphicon-eye-open preview-element"
              @click="dispatch_preview_element( elem_id )"
        ></span>
    </div>
    {{ widget_view }}
</div>
</template>