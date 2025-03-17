<?php
namespace Express;

// ... (Código de la clase View y los traits) ...

class UploadFiles extends View
{
    public $form;
    public $container;
    public $input;
    public $text;
    public $preview;
    public $button;

    public function __construct()
    {
        parent::__construct();

        // Declaración de objetos
        $this->form = new View();
        $this->container = new View();
        $this->input = new View();
        $this->text = new View();
        $this->preview = new View();
        $this->button = new View();

        // Configuración de atributos y estilos
        $this->form
            ->Tag('form')
            ->Attributes([
                'enctype' => 'multipart/form-data',
                'method' => 'post',
                'action' => '/cargar_archivos',
            ]);

        $this->container
            ->Attributes([
                'onclick' => $this->input->attributes["id"] . '.click();',
                'ondragover' => 'event.preventDefault(); this.style.borderColor = \'#007bff\';',
                'ondragleave' => 'this.style.borderColor = \'#ccc\';',
                'ondrop' => 
                'event.preventDefault(); this.style.borderColor = \'#ccc\'; ' 
                . $this->input->attributes["id"] . '.files = event.dataTransfer.files; 
                displayFilePreview(event.dataTransfer.files, \'' . $this->preview->attributes["id"] . '\'); ' 
                . $this->button->attributes["id"] . '.style.display = \'block\';'
            ])
            ->Style([
                'display' => 'flex',
                'flexDirection' => 'column',
                'alignItems' => 'center',
                'padding' => '20px',
                'border' => '2px dashed #ccc',
                'cursor' => 'pointer',
                'transition' => 'border-color 0.3s ease'
            ]);

        $this->input
            ->Tag('input')
            ->Attributes([
                'type' => 'file',
                'name' => 'archivos[]',
                'multiple' => 'multiple',
                'onchange' => 
                'displayFilePreview(this.files, \'' . $this->preview->attributes["id"] . '\');'
                . $this->button->attributes["id"] . '.style.display = \'block\';'
            ])
            ->Style
            ([
                'display' => 'none'
            ]);

        $this->text
            ->Tag('p')
            ->Children('Arrastra y suelta un archivo aquí o haz clic para seleccionar');

        $this->preview
            ->Style(['marginTop' => '10px']);

        $this->button
            ->Tag('button')
            ->Attributes([
                'type' => 'submit',
            ])
            ->Style([
                'marginTop' => '20px',
                'padding' => '10px 20px',
                'backgroundColor' => '#007bff',
                'color' => 'white',
                'border' => 'none',
                'borderRadius' => '5px',
                'cursor' => 'pointer',
                'transition' => 'background-color 0.3s ease',
                'display' => 'none'
                ,
                'background-color' => '#3498db'
                ,
                'border-radius' => '8px'
                ,
                'cursor' => 'pointer'
            ])
            ->Children('Subir archivos');

        // Establecimiento de relaciones
        $this->container->Children_add([
            $this->input,
            $this->text,
            $this->preview
        ]);

        $this->form->Children_add([
            "
                <script>
                    function displayFilePreview(files, previewId) {
                        console.log('FILES' + files.length)
                        const preview = document.getElementById(previewId);
                        preview.innerHTML = ''; // Limpiar la lista anterior
                    
                        if (files && files.length > 0) {
                            const fileList = document.createElement('ul');
                            for (let i = 0; i < files.length; i++) {
                                const file = files[i];
                                const listItem = document.createElement('li');
                                listItem.textContent = file.name;
                                fileList.appendChild(listItem);
                            }
                            preview.appendChild(fileList);
                        }
                    }
                </script>
            "
            ,
            $this->container,
            $this->button
        ]);

        // Asignación de propiedades
        $this->tag = $this->form->tag;
        $this->attributes = $this->form->attributes;
        $this->children = $this->form->children;
        $this->style = $this->form->style;
    }
}
?>