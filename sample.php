<?php

require_once dirname(__FILE__) . '/php-xml-helper.php';

// *******************************************************

$xml = new XML("sample.xml");
$tags = $xml -> children('/hamy/fieldset:tags');

// *******************************************************

function e($v)
{
    echo $v;
}

// *******************************************************
?>

<table style="border-color: gray;" border="2" cellspacing="2">
    <tbody>
        <tr>
            <td>
                <h3><?php e($tags -> label); ?></h3>
                <pre><?php e($tags -> description); ?></pre>
            </td>
        </tr>
        
        <?php
        foreach ($tags->field as $field) : ?>
        
            <!-- TEXTBOX ************************************************************** -->
            
            <?php
                if($field -> name == 'textbox'):
                    $html = "";
                    foreach ($field->html as $name => $value) {
                        if($name === "content")
                            continue;
                        
                        $html .= $name . "='" . $value . "' ";
                    }
            ?>
        
            <tr>
                <td>
                    <pre>
                         <?php e($field -> description); ?>
                    </pre>
                    
                    <?php e($field -> label); ?>
                    
                    <input type="text" <?php e($html); ?> />
                </td>
            </tr>
            
            <?php endif; ?>
            
            <!-- TEXTAREA ************************************************************** -->
            
            <?php
                if($field -> name == 'textarea'):
                    $html = "";
                    foreach ($field->html as $name => $value) {
                        if($name === "content")
                            continue;
                        
                        $html .= $name . "='" . $value . "' ";
                    }
            ?>
        
            <tr>
                <td>
                    <pre>
                         <?php e($field -> description); ?>
                    </pre>
                    
                    <?php e($field -> label); ?>
                    
                    <textarea <?php e($html); ?>><?php e($field->html->content); ?></textarea>
                </td>
            </tr>
            
            <?php endif; ?>
            
            <!-- COMBOBOX ************************************************************** -->
            
            <?php
                if($field -> name == 'combobox'):
                    $html = "";
                    foreach ($field->html as $name => $value) {
                        if($name === "option")
                            continue;
                        
                        $html .= $name . "='" . $value . "' ";
                    }
            ?>
        
            <tr>
                <td>
                    <pre>
                         <?php e($field -> description); ?>
                    </pre>
                    
                    <?php e($field -> label); ?>
                    
                    <select <?php e($html); ?>>
                        
                        <?php
                            foreach ($field->html->option as $option):
                                
                                $option_html = "";
                                
                                foreach ($option as $name => $value) {
                                    if($name === "content")
                                        continue;
                                    
                                    $option_html .= $name . "='" . $value . "' ";
                                }
                                
                            ?>
                            
                            <option <?php e($option_html); ?>>
                                <?php e($option->content); ?>
                            </option>
                            
                            <?php
                            endforeach;
                        ?>
                        
                    </select>
                </td>
            </tr>
            
            <?php endif; ?>
                
        <?php endforeach; ?>
    </tbody>
</table>
