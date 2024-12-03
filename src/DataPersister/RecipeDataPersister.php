<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Recipe;
use App\Repository\CategoryRepository;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class RecipeDataPersister implements ContextAwareDataPersisterInterface
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, Security $security, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->categoryRepository = $categoryRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Recipe;
    }

    public function persist($data, array $context = []): Recipe
    {
        // Si la recette est nouvelle et que l'utilisateur est connecté
        if ($data instanceof Recipe && !$data->getAuthor() && $this->security->getUser()) {
            $data->setAuthor($this->security->getUser());
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
