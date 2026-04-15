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
        return mysqli_fetch_assoc($result);
    }

    public function updateSettings(string $site_title, string $site_desc, string $theme_color, string $seo_keywords, string $hero_banner = null) {
        $site_title = mysqli_real_escape_string($this->db, $site_title);
        $site_desc = mysqli_real_escape_string($this->db, $site_desc);
        $theme_color = mysqli_real_escape_string($this->db, $theme_color);
        $seo_keywords = mysqli_real_escape_string($this->db, $seo_keywords);

        if ($hero_banner != null) {
            $query = "UPDATE settings SET site_title='$site_title', site_desc='$site_desc', theme_color='$theme_color', seo_keywords='$seo_keywords', hero_banner='$hero_banner' WHERE id=1";
        } else {
            $query = "UPDATE settings SET site_title='$site_title', site_desc='$site_desc', theme_color='$theme_color', seo_keywords='$seo_keywords' WHERE id=1";
        }

        return mysqli_query($this->db, $query);
    }
}
?>