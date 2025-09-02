<?php

namespace App\Services;

interface ServiceInterface
{
    /**
     * Récupérer tous les éléments
     */
    public function getAll();

    /**
     * Récupérer un élément par ID
     */
    public function find(int $id);

    /**
     * Créer un nouvel élément
     */
    public function create(array $data);

    /**
     * Mettre à jour un élément
     */
    public function update(int $id, array $data);

    /**
     * Supprimer un élément
     */
    public function delete(int $id);
}
