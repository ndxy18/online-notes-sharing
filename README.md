# 📚 NotesHub — Online Notes Sharing System

Full working PHP + MySQL project (tera synopsis ke hisaab se banaya gaya hai).
Student notes upload/search/download kar sakte hain, admin approve/reject/manage karta hai.

---

## 🧩 Kya-kya bana hai

- **Student side:** Register, Login, Dashboard, Upload Notes, My Notes (edit/delete), Browse & Search with filters (subject/category/semester/course), View note, Download note
- **Admin side:** Dashboard (stats), Manage Notes (approve/reject/delete), Manage Users (block/unblock/delete), Manage Categories (add/delete)
- **Security:** Passwords bcrypt hashed, prepared statements (SQL injection safe), file-type + size validation on upload, uploads folder PHP-execution blocked
- **Design:** Fully mobile-responsive (phone/tablet/laptop sab pe khulega), notebook/index-card themed UI

---

## 🛠️ Setup — Step by Step (XAMPP)

1. **XAMPP install karo** (agar pehle se nahi hai): https://www.apachefriends.org
2. Is poore `notes-sharing-system` folder ko copy karke yaha paste karo:
   ```
   C:\xampp\htdocs\notes-sharing-system
   ```
   (Tu already `C:\xampp2\` use karta hai apne pichle projects mein — wahi pattern follow kar sakta hai, folder ka naam sirf note kar lena.)

3. **XAMPP Control Panel** kholo → **Apache** aur **MySQL** dono ko **Start** karo.

4. Browser mein jao: `http://localhost/phpmyadmin`
   - **Import** tab pe click karo
   - `database.sql` file choose karo (yeh project folder mein hai)
   - **Go** dabao — database `notes_sharing_system` ban jayega saari tables ke saath.

5. Ab browser mein kholo:
   ```
   http://localhost/notes-sharing-system/
   ```
   Website chalu ho jayegi! 🎉

---

## 🔑 Admin Login Banana (IMPORTANT)

`database.sql` mein ek default admin row hai, lekin uska password hash tere XAMPP PHP version ke saath match na ho toh yeh **guaranteed tarika** use karo:

1. Website par jaake normal **Sign up** karo (koi bhi email/password se).
2. `phpMyAdmin` kholo → `notes_sharing_system` database → `users` table.
3. Apne banaye hue account ki row dhundo, `role` column ki value **`student`** se **`admin`** kar do. Save/Go dabao.
4. Ab us email-password se login karo — seedha **Admin Dashboard** khulega.

---

## 📱 Mobile Pe Kaise Kholna Hai (Real World Use)

Taaki tera phone bhi isko access kar sake (same WiFi pe):

1. Jis laptop/PC pe XAMPP chal raha hai, uska **local IP address** nikaalo:
   - Windows: CMD mein `ipconfig` type karo → **IPv4 Address** dekho (e.g. `192.168.1.5`)
2. Laptop aur mobile **same WiFi network** pe hone chahiye.
3. Mobile ke browser mein type karo:
   ```
   http://192.168.1.5/notes-sharing-system/
   ```
   (apna wala IP daalna)
4. Windows Firewall popup aaye toh **Allow Access** karo Apache/PHP ke liye.

Bas — ab site tere mobile pe bhi chalegi, aur design already responsive hai (columns automatically stack ho jayenge chhoti screen pe).

**Internet pe real deployment** (kahi se bhi accessible) ke liye tujhe koi PHP+MySQL hosting chahiye hoga (jaise InfinityFree, Hostinger, ya 000webhost) — bata dena, wo steps bhi bata dunga alag se.

---

## 📂 Folder Structure

```
notes-sharing-system/
├── config/db.php              → database connection settings
├── includes/                  → header, footer, helper functions
├── assets/css/style.css       → all styling (mobile responsive)
├── assets/js/script.js        → mobile menu, alerts, confirm-delete
├── uploads/                   → uploaded note files get stored here
├── admin/                     → admin panel (dashboard, notes, users, categories)
├── index.php, login.php, register.php, dashboard.php,
│   upload.php, my_notes.php, browse.php, view_note.php,
│   download.php, delete_note.php
└── database.sql               → import this in phpMyAdmin first
```

---

## ⚙️ Customize karne ke liye

- **DB credentials change karni ho** (username/password) → `config/db.php` edit karo.
- **Max upload file size** (abhi 10MB hai) → `upload.php` mein `$max_size` variable change karo. Agar 10MB se bada chahiye toh `php.ini` mein bhi `upload_max_filesize` aur `post_max_size` badhana padega.
- **Colors/theme** → `assets/css/style.css` ke top mein `:root` variables hain, wahi se sab colors control hote hain.

---

## 📲 Mobile Pe App Jaisa Kholna (PWA — Install / Home Screen Icon)

Ab site ek **installable app** hai (PWA). Iska matlab:
- Phone ke home screen pe **NotesHub icon** aayega (📖 book icon)
- Icon tap karte hi **seedha app jaisa khulega** — browser address bar, tabs kuch nahi dikhega
- Basic **offline support** bhi hai (agar net na ho toh ek offline page dikhega, crash nahi hoga)

### Android (Chrome) pe install karna:
1. Mobile pe site kholo: `http://<tera-IP>/notes-sharing-system/`
2. Address bar ke paas ya navbar mein **"📲 Install App"** button dabao
   - Agar button nahi dikha, toh Chrome ke ⋮ menu → **"Add to Home screen"** / **"Install app"** dabao
3. Confirm karo — icon home screen pe aa jayega, naam hoga **NotesHub**

### iPhone (Safari) pe install karna:
1. Safari mein site kholo
2. Neeche **Share** icon (⬆️ box) dabao
3. **"Add to Home Screen"** select karo → Add
4. Icon home screen pe aa jayega

> ⚠️ **Important:** Agar tune project folder ka naam `notes-sharing-system` se alag rakha hai, toh `manifest.json` file mein `start_url` aur `scope` values ko apne folder naam ke hisaab se update kar lena.

> 💡 Real "search karo aur seedha app khule" wala experience (jaise Play Store apps) ke liye site ko live domain (hosting) pe daalna padega — local XAMPP sirf same-WiFi tak kaam karega. Bata dena agar hosting pe deploy karna hai, wo steps bhi bata dunga.

---

## 🚀 Future Scope (jaisa synopsis mein likha hai)

Mobile app, PDF online preview, ratings, notifications, bookmarks, AI recommendation, discussion forum — yeh sab baad mein add kiye ja sakte hain isi codebase ke upar.
