# ConnectLogs-for-Allmon3-and-Supermon
![Version](https://img.shields.io/badge/version-4.3-blue.svg)
![Platform](https://img.shields.io/badge/platform-Debian%2011%2F12+-orange.svg)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)

ConnectLogs is a web application that allows you to manage and visualize AllStar and EchoLink connection logs.

Application de gestion des journaux pour Allmon3 et Supermon avec une interface graphique moderne.

<img src="https://flagcdn.com/w20/us.png" width="20"/> **[English](#english)** | <img src="https://flagcdn.com/w20/fr.png" width="20"/> **[Français](#français)**

---

<a name="english"></a>
## <img src="https://flagcdn.com/w20/us.png" width="30"/> English

### 📋 Description

**ConnectLogs** is a web application that allows you to **manage and visualize AllStar and EchoLink connection logs**.

It is **compatible** with Allmon3 and Supermon, providing a **modern and intuitive interface** to review connection history.

When **AllStarLink 3** is installed without **Supermon**, it is necessary to **enable connection and disconnection logging for Allmon3** by installing **[Logfils for ASL3](https://github.com/CN8VX/logfils-asl3)**.

If **Supermon** is installed, you simply need to  **[enable connection and disconnection logging](https://www.dmr-maroc.com/astuces_tips_asl3.php#Activer_le_journal)** and **[configure log file rotation](https://www.dmr-maroc.com/astuces_tips_asl3.php#rotation_log)** to ensure efficient log management.

The **ConnectLogs** application displays logs in **clear, well-structured tables** and offers **automatic data refresh** for **real-time monitoring** of connections.

### ✨ Features

- ✅ Display of the last 10 AllStar and EchoLink logs on the home page
- ✅ View complete AllStar and EchoLink log history
- ✅ Modern and responsive graphical interface
- ✅ Automatic page refresh
- ✅ Password authentication (optional)
- ✅ Appearance customization (title, logo, sysop name)
- ✅ Compatible with Allmon3 and Supermon

### 🚀 Installation

#### Prerequisites

- A **web server** (Apache recommended)
- **PHP 7.4** or later
- Access to **AllStar / EchoLink log files**
- **Allmon3** with **Logfils for ASL3** installed  
  **or**
- **Supermon** installed with **log file rotation** properly configured

#### Installation procedure

1. Make sure you are in the `/var/www/html` directory

```bash
cd /var/www/html
```

2. Clone the repository from GitHub

```bash
sudo git clone https://github.com/CN8VX/ConnectLogs-for-Allmon3-and-Supermon.git connectlogs
cd
```
3. Permissions

```bash
sudo chmod 777 -R /var/www/html
```

### ⚙️ Configuration

**Edit the configuration file:**

```bash
sudo nano /var/www/html/connectlogs/include/config.php
```

#### Log directory path

By default, the log directory is configured to:
```php
$log_directory = '/var/log/asterisk/';
```

#### Path to astdb.txt file

**For Allmon3:**
```php
define("ASTDB_FILE", "/opt/logfils/astdb.txt");
```

**For Supermon:**
```php
define("ASTDB_FILE", "/var/www/html/supermon/astdb.txt");
```

#### Page display configuration

You can customize the appearance of your application by modifying the following parameters in `config.php`:

- **Page title**: `$page_title`
- **Logo path**: `LOGO_PATH`
- **Banner title**: `$TITLEBAN`
- **Sysop name**: `$SYSOP`

#### Authentication configuration

The application can be secured with a password authentication system.

**Enable/Disable authentication:**
```php
'login_required' => true,  // true = enabled, false = disabled
```

**Manage users:**
```php
'users' => [
    "admin" => "admin",
    "user"  => "123456",
    "user1" => "user123"
]
```

**Display user information:**
```php
'show_user_info' => true,  // Displays username and logout button
```

### 🌐 Access the application

Open your browser and go to:
- `http://YOUR-IP/connectlogs/`
- or `http://YOUR-HOSTNAME/connectlogs/`

### 📄 License

This project is developed by [CN8VX](https://www.qrz.com/db/CN8VX) under **GNU General Public License v3.0**.

### 👤 Author

**CN8VX**
- Website: [dmr-maroc.com](https://www.dmr-maroc.com)
- GitHub: [@CN8VX](https://github.com/CN8VX)
- 📧 **Email**: [cn8vx.ma@gmail.com](mailto:cn8vx.ma@gmail.com)

### 🤝 Support and Suggestions

All questions, issues or suggestions are welcome! Feel free to:
- Report bugs
- Suggest improvements
- Share your suggestions

### 📞 Support

For any questions or issues:
- Check the [Allmon3 documentation](https://github.com/AllStarLink/allmon3)
- 📧 **Email**: [cn8vx.ma@gmail.com](mailto:cn8vx.ma@gmail.com)

---

**73 from [CN8VX](https://www.qrz.com/db/CN8VX)** 📻

*If you like this project, don't hesitate to put a ⭐ on the repository!*

---

<a name="français"></a>
## <img src="https://flagcdn.com/w20/fr.png" width="30"/> Français

### 📋 Description

**ConnectLogs** est une application web qui permet de **gérer et visualiser les fichiers de journalisation** des connexions **Allmon3** et **Supermon**.

Elle est compatible avec Allmon3 et Supermon, et offre une **interface moderne et intuitive** pour consulter l’historique des connexions.

Lorsque **AllStarLink 3** est installé sans **Supermon**, il est nécessaire d’**activer la journalisation des connexions et déconnexions pour Allmon3** en installant **[Logfils for ASL3](https://github.com/CN8VX/logfils-asl3/tree/main?tab=readme-ov-file#français)**.

Si **Supermon** est installé, il suffit d’**[activer la journalisation des connexions et déconnexions](https://www.dmr-maroc.com/astuces_tips_asl3.php#Activer_le_journal)** et de **[configurer la rotation des fichiers de logs](https://www.dmr-maroc.com/astuces_tips_asl3.php#rotation_log)** afin d’assurer une gestion optimale des journaux.

L’application **ConnectLogs** affiche les logs sous forme de **tableaux clairs et lisibles**, et propose un **rafraîchissement automatique** des données pour un suivi **en temps réel** des connexions.

### ✨ Fonctionnalités

- ✅ Affichage des 10 derniers logs AllStar et EchoLink sur la page d'accueil
- ✅ Consultation de l'historique complet des logs AllStar et EchoLink
- ✅ Interface graphique moderne et responsive
- ✅ Rafraîchissement automatique des pages
- ✅ Authentification par mot de passe (optionnelle)
- ✅ Personnalisation de l'apparence (titre, logo, nom du sysop)
- ✅ Compatible avec Allmon3 et Supermon

### 🚀 Installation

#### Prérequis

- **Serveur web** (Apache recommandé)
- **PHP 7.4** ou supérieur
- Accès aux fichiers de **logs AllStar/EchoLink**
- **Allmon3** avec **Logfils for ASL3** installé  
  **ou**
- **Supermon** installé avec **la rotation des fichiers de logs** configurée

#### Procédure d'installation

1. Assurez-vous d'être dans le répertoire `/var/www/html`

```bash
cd /var/www/html
```

2. Clonez le dépôt depuis GitHub

```bash
sudo git clone https://github.com/CN8VX/ConnectLogs-for-Allmon3-and-Supermon.git connectlogs
cd
```
3. Autorisations

```bash
sudo chmod 777 -R /var/www/html
```

### ⚙️ Configuration

**Éditez le fichier de configuration :**

```bash
sudo nano /var/www/html/connectlogs/include/config.php
```

#### Chemin du répertoire des logs

Par défaut, le répertoire des logs est configuré sur :
```php
$log_directory = '/var/log/asterisk/';
```

#### Chemin du fichier astdb.txt

**Pour Allmon3 :**
```php
define("ASTDB_FILE", "/opt/logfils/astdb.txt");
```

**Pour Supermon :**
```php
define("ASTDB_FILE", "/var/www/html/supermon/astdb.txt");
```

#### Configuration de l'affichage de la page

Vous pouvez personnaliser l'apparence de votre application en modifiant les paramètres suivants dans `config.php` :

- **Titre de la page** : `$page_title`
- **Chemin du logo** : `LOGO_PATH`
- **Titre de la bannière** : `$TITLEBAN`
- **Nom du sysop** : `$SYSOP`

#### Configuration de l'authentification

L'application peut être sécurisée avec un système d'authentification par mot de passe.

**Activer/Désactiver l'authentification :**
```php
'login_required' => true,  // true = activé, false = désactivé
```

**Gérer les utilisateurs :**
```php
'users' => [
    "admin" => "admin",
    "user"  => "123456",
    "user1" => "user123"
]
```

**Afficher les informations utilisateur :**
```php
'show_user_info' => true,  // Affiche le nom d'utilisateur et le bouton de déconnexion
```

### 🌐 Accès à l'application

Ouvrez votre navigateur et accédez à :
- `http://VOTRE-IP/connectlogs/`
- ou `http://VOTRE-HOSTNAME/connectlogs/`

### 📄 Licence

Ce projet est développé par [CN8VX](https://www.qrz.com/db/CN8VX) sous licence **GNU General Public License v3.0**.

### 👤 Auteur

**CN8VX**
- Website: [dmr-maroc.com](https://www.dmr-maroc.com)
- GitHub: [@CN8VX](https://github.com/CN8VX)
- 📧 **Email** : [cn8vx.ma@gmail.com](mailto:cn8vx.ma@gmail.com)

### 🤝 Support et Suggestions

Toutes questions, problèmes ou suggestions sont les bienvenus ! N'hésitez pas à :
- Signaler des bugs
- Proposer des améliorations
- Partager vos suggestions

### 📞 Support

Pour toute question ou problème :
- Consultez la [documentation Allmon3](https://github.com/AllStarLink/allmon3)
- 📧 **Email** : [cn8vx.ma@gmail.com](mailto:cn8vx.ma@gmail.com)

---

**73 de [CN8VX](https://www.qrz.com/db/CN8VX)** 📻

*Si vous aimez ce projet, n'hésitez pas à mettre une ⭐ sur le dépôt !
