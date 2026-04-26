<?php
class SettingsModel {
    /** @var mysqli */
    private $db;

    public function __construct(mysqli $conn) {
        $this->db = $conn;
    }

    public function getSettings() {
        $query = "SELECT * FROM settings WHERE id = 1";
        $result = mysqli_query($this->db, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return [];
    }

    public function updateSettings(array $data, string $hero_banner = null) {
        $site_title = mysqli_real_escape_string($this->db, $data['site_title'] ?? '');
        $site_desc = mysqli_real_escape_string($this->db, $data['site_desc'] ?? '');
        $seo_keywords = mysqli_real_escape_string($this->db, $data['seo_keywords'] ?? '');
        
        $theme_color = mysqli_real_escape_string($this->db, $data['theme_color'] ?? '#254794');
        $admin_theme_color = mysqli_real_escape_string($this->db, $data['admin_theme_color'] ?? '#2563eb');
        $admin_sidebar_color = mysqli_real_escape_string($this->db, $data['admin_sidebar_color'] ?? '#1e293b');
        
        // INI TAMBAHANNYA BUAT TEKS ADMIN
        $admin_text_color = mysqli_real_escape_string($this->db, $data['admin_text_color'] ?? '#1e293b');
        
        $header_color = mysqli_real_escape_string($this->db, $data['header_color'] ?? '#ffffff');
        $footer_color = mysqli_real_escape_string($this->db, $data['footer_color'] ?? '#1e293b');
        $text_color = mysqli_real_escape_string($this->db, $data['text_color'] ?? '#333333');
        $header_text_color = mysqli_real_escape_string($this->db, $data['header_text_color'] ?? '#333333');
        $footer_text_color = mysqli_real_escape_string($this->db, $data['footer_text_color'] ?? '#9ca3af');
        
        $font_family = mysqli_real_escape_string($this->db, $data['font_family'] ?? 'Plus Jakarta Sans');
        
        $contact_phone = mysqli_real_escape_string($this->db, $data['contact_phone'] ?? '');
        $contact_email = mysqli_real_escape_string($this->db, $data['contact_email'] ?? '');
        $contact_ig = mysqli_real_escape_string($this->db, $data['contact_ig'] ?? '');
        $contact_address = mysqli_real_escape_string($this->db, $data['contact_address'] ?? '');

        $query = "UPDATE settings SET 
            site_title='$site_title', 
            site_desc='$site_desc', 
            seo_keywords='$seo_keywords', 
            theme_color='$theme_color', 
            admin_theme_color='$admin_theme_color', 
            admin_sidebar_color='$admin_sidebar_color', 
            admin_text_color='$admin_text_color', 
            header_color='$header_color', 
            footer_color='$footer_color', 
            text_color='$text_color', 
            header_text_color='$header_text_color', 
            footer_text_color='$footer_text_color', 
            font_family='$font_family', 
            contact_phone='$contact_phone', 
            contact_email='$contact_email', 
            contact_ig='$contact_ig', 
            contact_address='$contact_address'";

        if ($hero_banner != null) {
            $hero_banner = mysqli_real_escape_string($this->db, $hero_banner);
            $query .= ", hero_banner='$hero_banner'";
        }

        $query .= " WHERE id=1";
        return mysqli_query($this->db, $query);
    }
}
?>