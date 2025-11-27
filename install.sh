#!/bin/bash

###############################################################################
# Script d'installation automatique - Elie_ProductAttribute
# Compatible Magento 2.4.8-p3 / PHP 8.4
###############################################################################

set -e  # Arrêter en cas d'erreur

# Couleurs pour l'affichage
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║  Installation Elie_ProductAttribute Module              ║${NC}"
echo -e "${GREEN}║  Magento 2.4.8-p3 / PHP 8.4                              ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

# Vérifier que nous sommes dans le répertoire Magento
if [ ! -f "bin/magento" ]; then
    echo -e "${RED}❌ Erreur: bin/magento non trouvé${NC}"
    echo "Ce script doit être exécuté depuis la racine de Magento"
    exit 1
fi

# Vérifier la version PHP
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo -e "${YELLOW}📌 Version PHP détectée: ${PHP_VERSION}${NC}"

if [[ ! $PHP_VERSION =~ ^8\.[1-4] ]]; then
    echo -e "${RED}⚠️  Attention: PHP 8.1+ recommandé (détecté: $PHP_VERSION)${NC}"
fi

# Demander confirmation
echo ""
echo -e "${YELLOW}Cette installation va :${NC}"
echo "  1. Copier le module dans app/code/Elie/ProductAttribute"
echo "  2. Activer le module"
echo "  3. Exécuter setup:upgrade"
echo "  4. Recompiler le code"
echo "  5. Déployer les fichiers statiques"
echo ""
read -p "Continuer ? (y/n) " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}Installation annulée${NC}"
    exit 0
fi

# Demander si mode maintenance souhaité
echo ""
read -p "Activer le mode maintenance ? (recommandé en production) (y/n) " -n 1 -r
echo ""
MAINTENANCE_MODE=$REPLY

if [[ $MAINTENANCE_MODE =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}🔒 Activation du mode maintenance...${NC}"
    php bin/magento maintenance:enable
fi

# Étape 1: Copier le module
echo ""
echo -e "${GREEN}📁 Étape 1/7: Copie du module...${NC}"
mkdir -p app/code/Elie
cp -r ProductAttribute/ProductAttribute app/code/Elie/
echo -e "${GREEN}✅ Module copié${NC}"

# Étape 2: Vérifier les permissions
echo ""
echo -e "${GREEN}🔐 Étape 2/7: Vérification des permissions...${NC}"
chmod -R 755 app/code/Elie/ProductAttribute
echo -e "${GREEN}✅ Permissions définies${NC}"

# Étape 3: Activer le module
echo ""
echo -e "${GREEN}⚡ Étape 3/7: Activation du module...${NC}"
php bin/magento module:enable Elie_ProductAttribute
echo -e "${GREEN}✅ Module activé${NC}"

# Étape 4: Nettoyer les caches avant upgrade
echo ""
echo -e "${GREEN}🗑️  Étape 4/7: Nettoyage des caches...${NC}"
rm -rf var/cache/* var/page_cache/* var/view_preprocessed/* generated/*
echo -e "${GREEN}✅ Caches nettoyés${NC}"

# Étape 5: Setup upgrade (applique le Data Patch)
echo ""
echo -e "${GREEN}🔄 Étape 5/7: Exécution de setup:upgrade...${NC}"
php bin/magento setup:upgrade --keep-generated
echo -e "${GREEN}✅ Setup upgrade terminé${NC}"

# Étape 6: Compilation
echo ""
echo -e "${GREEN}⚙️  Étape 6/7: Compilation du code...${NC}"
php bin/magento setup:di:compile
echo -e "${GREEN}✅ Compilation terminée${NC}"

# Étape 7: Déploiement des fichiers statiques
echo ""
echo -e "${GREEN}📦 Étape 7/7: Déploiement des fichiers statiques...${NC}"
echo -e "${YELLOW}Langues à déployer ? (ex: fr_FR en_US) [Entrée pour all]:${NC}"
read LOCALES

if [ -z "$LOCALES" ]; then
    php bin/magento setup:static-content:deploy -f
else
    php bin/magento setup:static-content:deploy -f $LOCALES
fi
echo -e "${GREEN}✅ Déploiement terminé${NC}"

# Nettoyage final
echo ""
echo -e "${GREEN}🧹 Nettoyage final...${NC}"
php bin/magento cache:flush
echo -e "${GREEN}✅ Caches vidés${NC}"

# Désactiver le mode maintenance si activé
if [[ $MAINTENANCE_MODE =~ ^[Yy]$ ]]; then
    echo ""
    echo -e "${YELLOW}🔓 Désactivation du mode maintenance...${NC}"
    php bin/magento maintenance:disable
fi

# Vérifications post-installation
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║  Vérifications post-installation                         ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""

echo -e "${YELLOW}📌 Statut du module:${NC}"
php bin/magento module:status Elie_ProductAttribute

echo ""
echo -e "${YELLOW}📌 Attributs créés:${NC}"
php bin/magento eav:attribute:list catalog_product | grep -E "(product_select_attribute|product_custom_attribute|gender|brand|age_group|gtin)" || echo "Commande non disponible, vérifier manuellement dans l'admin"

# Afficher le résumé
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║  ✅ Installation terminée avec succès !                  ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}📋 Prochaines étapes:${NC}"
echo ""
echo "1. Vérifier dans l'admin:"
echo "   Admin → Stores → Attributes → Product"
echo "   Rechercher: gender, brand, age_group, gtin"
echo ""
echo "2. Configurer un produit:"
echo "   Admin → Catalog → Products → Edit"
echo "   Remplir les sections:"
echo "   - Custom Product Attribute"
echo "   - Brand-Gtin-Gender"
echo ""
echo "3. Tester l'affichage:"
echo "   Aller sur la page produit frontend"
echo "   Vérifier que les 2 blocs s'affichent"
echo ""
echo "4. Vérifier le SEO:"
echo "   View Source → Rechercher 'application/ld+json'"
echo ""
echo -e "${GREEN}📚 Documentation:${NC}"
echo "   - README.md : Documentation complète"
echo "   - MIGRATION_GUIDE.md : Guide de migration détaillé"
echo "   - TECHNICAL_CHANGES.md : Notes techniques"
echo ""
echo -e "${GREEN}🎉 Profitez de votre nouveau module !${NC}"
