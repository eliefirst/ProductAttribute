# 🔄 Guide de Migration - Magento 2.3.1 vers 2.4.8-p3

## ⚠️ Prérequis

- ✅ Magento 2.4.8-p3 installé
- ✅ PHP 8.1, 8.2, 8.3 ou 8.4
- ✅ Backup de la base de données
- ✅ Accès SSH au serveur

---

## 📋 Étape 1 : Vérification avant migration

### Vérifier la version actuelle du module

```bash
php bin/magento module:status | grep Elie_ProductAttribute
```

### Faire un backup de la base de données

```bash
php bin/magento setup:backup --db
```

---

## 🚀 Étape 2 : Déploiement du module mis à jour

### Option A : Mise à jour en place (recommandé)

```bash
# 1. Mettre le site en mode maintenance
php bin/magento maintenance:enable

# 2. Sauvegarder l'ancien module
cp -r app/code/Elie/ProductAttribute app/code/Elie/ProductAttribute.backup

# 3. Copier les nouveaux fichiers
# (via Git pull ou copie manuelle)

# 4. Supprimer les caches
rm -rf var/cache/* var/page_cache/* var/view_preprocessed/* pub/static/*

# 5. Exécuter setup:upgrade (applique le Data Patch)
php bin/magento setup:upgrade

# 6. Recompiler
php bin/magento setup:di:compile

# 7. Déployer les fichiers statiques
php bin/magento setup:static-content:deploy -f fr_FR en_US

# 8. Nettoyer le cache
php bin/magento cache:flush

# 9. Désactiver le mode maintenance
php bin/magento maintenance:disable
```

### Option B : Installation fraîche

Si le module n'existe pas encore :

```bash
# 1. Copier le module
mkdir -p app/code/Elie
cp -r ProductAttribute/ProductAttribute app/code/Elie/

# 2. Activer et installer
php bin/magento module:enable Elie_ProductAttribute
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```

---

## 🔍 Étape 3 : Vérifications post-migration

### 1. Vérifier que le module est actif

```bash
php bin/magento module:status Elie_ProductAttribute
```

**Résultat attendu :**
```
List of enabled modules:
Elie_ProductAttribute
```

### 2. Vérifier que les attributs sont créés

```bash
php bin/magento eav:attribute:list catalog_product | grep -E "(product_select_attribute|product_custom_attribute|gender|brand|age_group|gtin)"
```

**Résultat attendu :**
```
product_select_attribute
product_custom_attribute
gender
brand
age_group
gtin
```

### 3. Vérifier dans l'Admin

**Admin Panel** → Stores → Attributes → Product

Rechercher les attributs :
- ✅ `product_select_attribute`
- ✅ `product_custom_attribute`
- ✅ `gender`
- ✅ `brand`
- ✅ `age_group`
- ✅ `gtin`

### 4. Vérifier les groupes d'attributs

**Admin Panel** → Catalog → Products → Edit any product

Vérifier que les sections apparaissent :
- ✅ **Custom Product Attribute**
- ✅ **Brand-Gtin-Gender**

### 5. Tester l'affichage frontend

1. Éditer un produit et remplir les attributs
2. Aller sur la page produit frontend
3. Vérifier que les 2 blocs s'affichent après le prix

### 6. Vérifier le JSON-LD (SEO)

Aller sur une page produit et afficher le code source :

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "...",
  "brand": {...}
}
</script>
```

---

## 🛠️ Dépannage

### Erreur : "Module has not been installed yet"

**Solution :**
```bash
php bin/magento setup:upgrade --keep-generated
```

### Erreur : "Class not found"

**Solution :**
```bash
php bin/magento setup:di:compile
composer dump-autoload
```

### Les attributs ne s'affichent pas dans l'admin

**Solution :**
```bash
# Réindexer
php bin/magento indexer:reindex

# Vider le cache
php bin/magento cache:flush
```

### Les blocs ne s'affichent pas sur la page produit

**Vérifications :**

1. Les attributs ont des valeurs ?
```bash
# Vérifier en BDD
mysql> SELECT * FROM catalog_product_entity_varchar WHERE attribute_id IN (
    SELECT attribute_id FROM eav_attribute WHERE attribute_code = 'brand'
);
```

2. Le layout XML est chargé ?
```bash
# Vérifier les fichiers de layout
ls -la app/code/Elie/ProductAttribute/view/frontend/layout/
```

3. Cache vidé ?
```bash
php bin/magento cache:flush full_page layout
```

### Erreur PHP 8.4 : "Type error"

**Cause :** Code non compatible avec typage strict

**Solution :** Tous les fichiers ont été mis à jour avec `declare(strict_types=1);`

---

## 📊 Compatibilité des données

### Les données existantes sont-elles conservées ?

✅ **OUI** - Le Data Patch utilise `$eavSetup->removeAttribute()` puis `addAttribute()`, ce qui :
- Supprime l'ancienne définition d'attribut
- Recrée l'attribut avec les nouveaux paramètres
- **Conserve les données existantes** (valeurs stockées en BDD)

### Migration des valeurs

Aucune migration de données n'est nécessaire. Les valeurs existantes de :
- `product_select_attribute`
- `product_custom_attribute`

seront automatiquement préservées.

---

## 🗑️ Nettoyage (optionnel)

### Supprimer l'ancien fichier Setup

**Important :** Ne faire ceci qu'après avoir vérifié que tout fonctionne !

```bash
rm app/code/Elie/ProductAttribute/Setup/InstallData.php
```

**Note :** Ce fichier n'est plus utilisé dans Magento 2.4+, mais peut être laissé sans risque.

---

## 📝 Checklist de validation

- [ ] Module activé
- [ ] Tous les attributs créés (6 au total)
- [ ] Groupes d'attributs visibles dans l'admin
- [ ] Attributs affichés sur page produit backend
- [ ] Templates visibles sur page produit frontend
- [ ] JSON-LD généré dans le code source
- [ ] Pas d'erreurs dans les logs
- [ ] Cache vidé et recompilé

---

## 🎯 Tests recommandés en préprod

### Test 1 : Création de produit

1. Créer un nouveau produit
2. Remplir tous les attributs custom
3. Sauvegarder
4. Vérifier affichage frontend

### Test 2 : Modification de produit existant

1. Éditer un produit existant
2. Ajouter les nouveaux attributs Google Shopping
3. Sauvegarder
4. Vérifier affichage frontend

### Test 3 : Multiselect

1. Sélectionner plusieurs valeurs pour Gender (ex: Male + Unisex)
2. Sélectionner plusieurs valeurs pour Age Group (ex: Kids + Adult)
3. Vérifier affichage : "Male, Unisex"

### Test 4 : SEO

1. Remplir tous les champs sur un produit
2. Afficher la page produit frontend
3. View Source → chercher "application/ld+json"
4. Copier le JSON et valider sur : https://validator.schema.org/

### Test 5 : Performance

```bash
# Activer le profiler
php bin/magento deploy:mode:set developer

# Vérifier le temps de chargement
# Les ViewModels ne doivent pas ralentir la page
```

---

## 🔄 Rollback en cas de problème

Si la migration échoue :

```bash
# 1. Restaurer le backup du module
rm -rf app/code/Elie/ProductAttribute
mv app/code/Elie/ProductAttribute.backup app/code/Elie/ProductAttribute

# 2. Restaurer la base de données
mysql -u root -p DATABASE_NAME < var/backups/TIMESTAMP_db.sql

# 3. Recompiler
php bin/magento setup:di:compile
php bin/magento cache:flush
```

---

## 📞 Support

En cas de problème, fournir :
- Version Magento exacte
- Version PHP
- Logs d'erreurs (`var/log/system.log`, `var/log/exception.log`)
- Résultat de `php bin/magento module:status`

---

**✅ La migration est terminée une fois toutes les vérifications validées !**
