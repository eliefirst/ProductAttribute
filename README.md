# Elielweb_ProductAttribute - Module Magento 2

## 📋 Description

Module Magento 2 pour afficher des attributs produits personnalisés et des données Google Shopping/SEO sur la page produit.

**Package:** elielweb/module-productattribute
**Version:** 2.0.0
**Compatible:** Magento 2.4.8-p3 | PHP 8.1, 8.2, 8.3, 8.4

---

## ✨ Fonctionnalités

### Custom Product Attribute
- **Product Elie Status** : Select (Enable/Disable)
- **Product Elie Attribute Value** : Champ texte requis

### Brand-Gtin-Gender (Google Shopping / SEO)
- **Gender** : Multiselect (Male, Female, Unisex)
- **Brand** : Champ texte pour le nom de marque
- **Age Group** : Multiselect (Newborn, Infant, Toddler, Kids, Adult)
- **GTIN** : Champ texte pour les codes GTIN/EAN/UPC

### SEO
- Génération automatique de **JSON-LD Structured Data** (Schema.org)
- Optimisation pour Google Shopping Feed

---

## 📦 Installation

### Méthode 1: Via Composer (Recommandée pour Production3)

#### Installation depuis le repository Git

```bash
cd /data/www/magento2-3

# Ajouter le repository
composer config repositories.elielweb-productattribute git https://github.com/eliefirst/ProductAttribute.git

# Installer le module
composer require elielweb/module-productattribute:dev-claude/install-productattribute-module-b8DyE

# Activer le module
php bin/magento module:enable Elielweb_ProductAttribute
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
php bin/magento cache:flush
```

Le module sera installé automatiquement dans `/data/www/magento2-3/vendor/elielweb/module-productattribute`

### Méthode 2: Installation locale (développement)

```bash
# Créer le dossier vendor
mkdir -p /data/www/magento2-3/vendor/elielweb

# Copier le module
cp -r ProductAttribute/ProductAttribute /data/www/magento2-3/vendor/elielweb/module-productattribute

# Mettre à jour l'autoloader
cd /data/www/magento2-3
composer dump-autoload

# Activer le module
php bin/magento module:enable Elielweb_ProductAttribute
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
php bin/magento cache:flush
```

### Vérifier l'installation

```bash
php bin/magento module:status Elielweb_ProductAttribute
```

---

## ⚙️ Configuration Back-Office

Après l'installation, accéder à la configuration du module :

**Admin Panel** → **Stores** → **Configuration** → **Elielweb** → **ProductAttribute**

### Sections disponibles

#### 1. General Settings
- **Enable Module**: Activer/Désactiver le module

#### 2. Custom Attributes Settings
- **Display Custom Attributes on Frontend**: Afficher les attributs personnalisés sur la page produit
- **Attribute Label Color**: Couleur CSS pour les labels (ex: #555)

#### 3. Google Shopping Attributes
- **Enable JSON-LD Structured Data**: Ajouter les données structurées JSON-LD
- **Display Brand**: Afficher la marque
- **Display Gender**: Afficher le genre
- **Display Age Group**: Afficher le groupe d'âge
- **Display GTIN**: Afficher le code GTIN/EAN/UPC

---

## 🔄 Migration depuis Magento 2.3.1

### Modifications effectuées

| Fichier obsolète | Nouveau système |
|------------------|-----------------|
| `Setup/InstallData.php` | ✅ `Setup/Patch/Data/AddProductAttributes.php` |
| `module.xml` (setup_version) | ✅ Supprimé |

### Nouveaux fichiers créés

```
ProductAttribute/
├── Model/
│   └── Config/
│       └── Source/
│           ├── StatusOptions.php         (mis à jour)
│           ├── GenderOptions.php          (nouveau)
│           └── AgeGroupOptions.php        (nouveau)
├── Setup/
│   └── Patch/
│       └── Data/
│           └── AddProductAttributes.php   (nouveau)
├── ViewModel/
│   └── ProductAttributes.php              (nouveau)
├── view/
│   └── frontend/
│       ├── layout/
│       │   └── catalog_product_view.xml   (nouveau)
│       └── templates/
│           └── product/
│               └── view/
│                   ├── custom_attribute.phtml    (nouveau)
│                   └── google_shopping.phtml     (nouveau)
├── composer.json                          (nouveau)
└── registration.php                       (inchangé)
```

---

## 🎯 Utilisation

### 1. Configuration des attributs

**Admin Panel** → Catalog → Products → Edit Product

#### Section "Custom Product Attribute"
- Sélectionner le statut (Enable/Disable)
- Renseigner la valeur personnalisée (obligatoire)

#### Section "Brand-Gtin-Gender"
- Sélectionner le(s) genre(s) cible
- Renseigner le nom de la marque
- Sélectionner le(s) groupe(s) d'âge
- Ajouter le code GTIN/EAN/UPC

### 2. Affichage sur la page produit

Les attributs s'affichent automatiquement sur la page produit après le prix :

**Bloc 1 : Custom Product Attribute**
```
Product Elie Status: Enable
Product Elie Attribute Value: Bel Elixir Chain Necklace Yellow Gold
```

**Bloc 2 : Brand-Gtin-Gender**
```
Gender: Female
Brand: Redline
Age Group: Adult
Gtin: 3701029619804
```

### 3. Structured Data SEO

Le module génère automatiquement du JSON-LD conforme à Schema.org :

```json
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "Product Name",
  "sku": "SKU123",
  "brand": {
    "@type": "Brand",
    "name": "Redline"
  },
  "gtin": "3701029619804",
  "audience": {
    "@type": "PeopleAudience",
    "suggestedGender": "Female",
    "suggestedMinAge": "Adult"
  }
}
```

---

## 🛠️ Personnalisation

### Modifier les options Gender

Éditer : `Model/Config/Source/GenderOptions.php`

```php
$this->_options = [
    ['label' => __('Male'), 'value' => 'male'],
    ['label' => __('Female'), 'value' => 'female'],
    ['label' => __('Unisex'), 'value' => 'unisex']
    // Ajouter vos options ici
];
```

### Modifier les options Age Group

Éditer : `Model/Config/Source/AgeGroupOptions.php`

### Personnaliser le style

Les templates incluent du CSS inline. Pour utiliser un fichier CSS externe :

1. Créer `view/frontend/web/css/product-attributes.css`
2. Déclarer dans `view/frontend/layout/catalog_product_view.xml` :

```xml
<page>
    <head>
        <css src="Elielweb_ProductAttribute::css/product-attributes.css"/>
    </head>
</page>
```

---

## 🔍 Débogage

### Vérifier si les attributs sont créés

```bash
php bin/magento eav:attribute:list catalog_product | grep -E "(product_select_attribute|product_custom_attribute|gender|brand|age_group|gtin)"
```

### Vérifier si le module est activé

```bash
php bin/magento module:status Elielweb_ProductAttribute
```

### Logs

Consulter : `var/log/system.log` et `var/log/exception.log`

---

## 📝 Notes importantes

### Compatibilité PHP 8.4
- ✅ Tous les fichiers utilisent `declare(strict_types=1);`
- ✅ Typage fort des paramètres et retours de méthodes
- ✅ Utilisation de constructeurs modernes

### Best Practices Magento 2.4.8
- ✅ Data Patch au lieu de InstallData
- ✅ ViewModel au lieu de Block avec logique métier
- ✅ Pas de setup_version dans module.xml
- ✅ Utilisation de ProductRepositoryInterface

### Sécurité
- ✅ Échappement XSS avec `$block->escapeHtml()`
- ✅ `@noEscape` uniquement pour JSON-LD validé
- ✅ Pas d'injection SQL (utilisation de l'ORM Magento)

---

## 🐛 Support

Pour signaler un bug ou demander une fonctionnalité, créer une issue sur le repository Git.

---

## 📄 License

Proprietary - Usage interne uniquement

---

## 🎉 Changelog

### Version 2.0.0 (2025-01-XX)
- ✅ Migration vers Magento 2.4.8-p3
- ✅ Compatibilité PHP 8.4
- ✅ Remplacement InstallData par Data Patch
- ✅ Ajout des attributs Google Shopping (Gender, Brand, Age Group, GTIN)
- ✅ Création des templates d'affichage sur page produit
- ✅ Génération automatique de Structured Data JSON-LD
- ✅ Ajout ViewModel pour la séparation des préoccupations

### Version 1.0.0 (2019-XX-XX)
- 🔵 Version initiale pour Magento 2.3.1
- 🔵 Attributs custom basiques
