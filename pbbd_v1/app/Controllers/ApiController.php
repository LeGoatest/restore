<?php

namespace App\Controllers;

use App\Models\Block;
use App\Models\Blueprint;
use App\Models\Document;

class ApiController extends BaseController
{
    public function handle($action)
    {
        $id = $_GET['id'] ?? null;

        switch ($action) {
            case 'save':
                $this->saveDocument($id);
                break;
            case 'add_field_row':
                $this->addFieldRow();
                break;
            case 'create_block':
                $this->createBlock();
                break;
            case 'add_block':
                $this->addBlockToDocument($id);
                break;
            case 'remove_block':
                $this->removeBlockFromDocument($id);
                break;
            case 'move_block':
                $this->moveBlockInDocument($id);
                break;
            case 'create_blueprint':
                $this->createBlueprint();
                break;
            default:
                http_response_code(400);
                echo "Invalid API action.";
                break;
        }
    }

    private function saveDocument($id)
    {
        $document = new Document();
        $originalContent = $document->find($id)['content'];
        $originalContent = json_decode($originalContent, true);

        $newContent = $originalContent;
        foreach ($_POST['blocks'] as $index => $blockData) {
            if (isset($blockData['data'])) {
                foreach ($blockData['data'] as $fieldHandle => $value) {
                    $newContent['blocks'][$index]['data'][$fieldHandle] = $value;
                }
            }
        }

        $document->update($id, ['content' => json_encode($newContent)]);

        header('HX-Trigger: show-message');
        $this->render('edit-form', ['document' => $document->find($id)]);
    }

    private function addFieldRow()
    {
        $this->render('partials/field-row');
    }

    private function createBlock()
    {
        $block = new Block();
        $block->create([
            'name' => $_POST['name'] ?? 'Untitled Block',
            'handle' => $_POST['handle'] ?? 'untitled-' . time(),
            'template' => $_POST['template'] ?? '<div></div>',
            'schema' => json_encode(['fields' => array_values($_POST['fields'] ?? [])]),
        ]);

        $this->render('partials/builder-success', ['name' => $_POST['name']]);
    }

    private function addBlockToDocument($id)
    {
        $document = new Document();
        $content = $document->find($id)['content'];
        $content = json_decode($content, true);

        $block = new Block();
        $blockSchema = $block->findByHandle($_POST['block_handle'])['schema'];
        $blockSchema = json_decode($blockSchema, true);

        $newBlock = [
            'block_handle' => $_POST['block_handle'],
            'data' => [],
        ];

        foreach ($blockSchema['fields'] as $field) {
            $newBlock['data'][$field['handle']] = '';
        }

        $content['blocks'][] = $newBlock;

        $document->update($id, ['content' => json_encode($content)]);

        $this->render('edit-form', ['document' => $document->find($id)]);
    }

    private function removeBlockFromDocument($id)
    {
        $document = new Document();
        $content = $document->find($id)['content'];
        $content = json_decode($content, true);

        $index = (int)$_GET['index'];

        if (isset($content['blocks'][$index])) {
            array_splice($content['blocks'], $index, 1);
        }

        $document->update($id, ['content' => json_encode($content)]);

        $this->render('edit-form', ['document' => $document->find($id)]);
    }

    private function moveBlockInDocument($id)
    {
        $document = new Document();
        $content = $document->find($id)['content'];
        $content = json_decode($content, true);

        $index = (int)$_GET['index'];
        $direction = $_GET['direction'];

        $newIndex = $direction === 'up' ? $index - 1 : $index + 1;

        if (isset($content['blocks'][$index]) && isset($content['blocks'][$newIndex])) {
            $block = $content['blocks'][$index];
            array_splice($content['blocks'], $index, 1);
            array_splice($content['blocks'], $newIndex, 0, [$block]);
        }

        $document->update($id, ['content' => json_encode($content)]);

        $this->render('edit-form', ['document' => $document->find($id)]);
    }

    private function createBlueprint()
    {
        $blueprint = new Blueprint();
        $blueprint->create([
            'name' => $_POST['name'] ?? 'Untitled Blueprint',
            'handle' => $_POST['handle'] ?? 'untitled-' . time(),
            'schema' => json_encode(['block_area' => ['allowed_blocks' => $_POST['allowed_blocks'] ?? []]]),
        ]);

        $this->render('partials/builder-success', ['name' => $_POST['name']]);
    }
}
