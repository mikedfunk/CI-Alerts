CI (CodeIgniter) Alerts
============================

I was looking at using flashdata for alerts, but it didn't seem to fit the bill. What if I want to add multiple alerts and have them displayed when I go to the next page? What if I want to set the type of alert (success, error, etc.) so I can style it appropriately? What if I want to display two success alerts and one warning alert? I don't want to have a ton of code in each view like this, which will limit me to one alert of each type anyway:

    <?php if ($this->session->flashdata('success') !== FALSE): ?>
    <div class="alert alert-success"><?=$this->session->flashdata('success')?></div><!--alert-->
    <?php endif;
    if ($this->session->flashdata('error') !== FALSE): ?>
    <div class="alert alert-error"><?=$this->session->flashdata('error')?></div><!--alert-->
    <?php endif; ?>
    // etc...

CI Alerts aims to solve this problem. It allows you to add alerts of type success, error, info, or warning to flashdata and later display them. It adds the alerts to arrays for each one, so the success flashdata is an array with each success alert in it. You can display all alerts of a certain type or all alerts. The wrapping HTML is set in the config file and has separate html for each type. Since it's flashdata it only lasts one page reload by default, so keep that in mind.

Setup
----------------------------

1. Clone this into **application/third_party**
2. Add this to the ```$autoload['packages']``` array in **application/config/autoload.php**:  ```APPPATH.'third_party/ci_alerts'``` or do ```$this->load->add_package_path(APPPATH.'third_party/ci_alerts');```
3. Edit **config/alerts_config.php** with whatever you want to use to display alerts

Usage
----------------------------

Load library

    $this->load->library('alerts');

Set Success, Set Error, Set Info, Set Warning

    $this->alerts->set_success($value);
    
Display Alerts

    $this->alerts->display_all($optional_type);

HTML wrappers are configurable in **alerts_config.php**. There are also methods for retrieving alerts in arrays for flexibility. Have fun!