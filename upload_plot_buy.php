<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('127.0.0.1', 'root', '', 'miniproj');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $place = $_POST['place'];
    $price = $_POST['price'];
    $state = $_POST['state'];
    $size = $_POST['size'];
    $district = $_POST['district'];
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);

    // Validate inputs
    if (empty($place) || empty($price) || empty($state) || empty($district) || empty($photo) || empty($size)) {
        echo "All fields are required.";
    } elseif (!is_numeric($size) || $size <= 0) {
        echo "Size must be a positive number.";
    } elseif (!is_numeric($price) || $price <= 0) {
        echo "Price must be a positive number.";
    } else {
        // Check for duplicate properties
        $checkSql = "SELECT * FROM properties WHERE place = ? AND price = ? AND state = ? AND district = ? AND size = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("sssss", $place, $price, $state, $district, $size); // Added size here
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "This property already exists.";
        } else {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                // Prepare the SQL statement
                $sql = "INSERT INTO properties (user_id, place, price, photo, property_type, transaction_type, state, district, size) 
                        VALUES (?, ?, ?, ?, 'plot', 'buy', ?, ?, ?)";

                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die("Error preparing the statement: " . $conn->error);
                }

                // Correct binding parameters
                $stmt->bind_param("issssss", $_SESSION['user_id'], $place, $price, $photo, $state, $district, $size);

                if ($stmt->execute()) {
                    echo "House uploaded successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                die("Sorry, there was an error uploading your file.");
            }
        }

        $checkStmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload House for Rent - RealEstiMate</title>
    <link rel="stylesheet" href="common.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        header {
            background-color: #3498db;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .upload-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
    </style>
 
    <script>
  const stateDistrictMap = {
            "Andhra Pradesh": {
                "Anantapur": ["Anantapur", "Guntakal", "Kadiri", "Penukonda"],
                "Chittoor": ["Chittoor", "Tirupati", "Vellore", "Puttur"],
                "East Godavari": ["Kakinada", "Rajahmundry", "Peddaganjam", "Sakinetipalle"],
                "Guntur": ["Guntur", "Tenali", "Narsaraopet", "Bapatla"],
                "Krishna": ["Vijayawada", "Machilipatnam", "Gudivada", "Nandigama"],
                "Kurnool": ["Kurnool", "Nandikotkur", "Yemmiganur", "Adoni"],
                "Nellore": ["Nellore", "Gudur", "Sullurpet", "Venkatagiri"],
                "Prakasam": ["Ongole", "Chirala", "Markapur", "Kandukur"],
                "Srikakulam": ["Srikakulam", "Amadalavalasa", "Rajam", "Palakonda"],
                "Visakhapatnam": ["Visakhapatnam", "Anakapalle", "Narsipatnam", "Peddaganjam"],
                "Vizianagaram": ["Vizianagaram", "Parvathipuram", "Nellimarla", "Bobbili"],
                "West Godavari": ["Eluru", "Bhimavaram", "Jangareddygudem", "Tadepalligudem"],
                "YSR Kadapa": ["Kadapa", "Proddatur", "Rajampet", "Jammalamadugu"],
                "Bapatla": ["Bapatla", "Chirala", "Peddaganjam", "Guntur"],
                "Palnadu": ["Narsaraopet", "Guntur", "Peddaganjam", "Macherla"]
            },
            "Arunachal Pradesh": {
                "Tawang": ["Tawang", "Jang", "Lumla", "Mongar"],
                "West Kameng": ["Bomdila", "Kalaktang", "Rupa", "Tenga"],
                "East Kameng": ["Seppa", "Papu Nallah", "Kangku", "Chayang Tajo"],
                "Papum Pare": ["Itanagar", "Naharlagun", "Doimukh", "Yachuli"],
                "Kurung Kumey": ["Koloriang", "Parbuk", "Sarli", "Raga"],
                "Kra Daadi": ["Karda", "Yangte", "Kaya", "Daring"],
                "Lower Subansiri": ["Ziro", "Raga", "Lali", "Joram"],
                "Upper Subansiri": ["Daporijo", "Taliha", "Silluk", "Subansiri"],
                "West Siang": ["Aalo", "Mechuka", "Tato", "Liromoba"],
                "East Siang": ["Pasighat", "Ruksin", "Mebo", "Jarkong"],
                "Siang": ["Pangin", "Boleng", "Jengging", "Rongkong"],
                "Upper Siang": ["Yingkiong", "Mariyang", "Mebo", "Geku"],
                "Lower Siang": ["Likabali", "Koyu", "Borisali", "Nirjuli"],
                "Lower Dibang Valley": ["Roing", "Anini", "Namsai", "Meka"],
                "Dibang Valley": ["Anini", "Dambuk", "Kari", "Dibang"],
                "Anjaw": ["Hawa", "Chungtang", "Hawac", "Mishmi Hills"],
                "Lohit": ["Tezu", "Namsai", "Mahadevpur", "Sunpura"],
                "Namsai": ["Namsai", "Miao", "Vijoynagar", "Namsai"],
                "Changlang": ["Changlang", "Margherita", "Miao", "Laju"],
                "Tirap": ["Tirap", "Khonsa", "Chungli", "Longding"],
                "Longding": ["Longding", "Kohima", "Changlang", "Miao"],
                "Pakke-Kessang": ["Pakke-Kessang", "Bhalukpong", "Seijosa", "Kalaktang"]
            },
            "Assam": {
                "Baksa": ["Baksa", "Barama", "Kokrajhar", "Kachugaon"],
                "Barpeta": ["Barpeta", "Sarbhog", "Bongaigaon", "Barama"],
                "Biswanath": ["Biswanath", "Gohpur", "Biswanath Chariali", "Bhalukmari"],
                "Bongaigaon": ["Bongaigaon", "Dabargaon", "Bongaigaon Town", "Abhayapuri"],
                "Cachar": ["Silchar", "Hailakandi", "Karimganj", "Cachar"],
                "Charaideo": ["Charaideo", "Sonari", "Nitaipukhuri", "Sivasagar"],
                "Chirang": ["Chirang", "Kajalgaon", "Bongaigaon", "Kokrajhar"],
                "Darrang": ["Mangaldoi", "Dalgaon", "Sankardev Nagar", "Darrang"],
                "Dhemaji": ["Dhemaji", "Jonai", "Silapathar", "North Lakhimpur"],
                "Dhubri": ["Dhubri", "Gauripur", "Bilasipara", "Chapor"],
                "Dibrugarh": ["Dibrugarh", "Tinsukia", "Lakhimpur", "Dhemaji"],
                "Goalpara": ["Goalpara", "Rangia", "Kokrajhar", "Bongaigaon"],
                "Golaghat": ["Golaghat", "Dhekiajuli", "Sarupathar", "Kaliabor"],
                "Hailakandi": ["Hailakandi", "Katlicherra", "Lala", "Hailakandi"],
                "Hojai": ["Hojai", "Lanka", "Hojai Town", "Kaki"],
                "Jorhat": ["Jorhat", "Majuli", "Teok", "Mariani"],
                "Kamrup Metropolitan": ["Guwahati", "Rangia", "Kamrup", "North Guwahati"],
                "Kamrup": ["Boko", "Rangia", "Mirza", "Kamrup"],
                "Karbi Anglong": ["Diphu", "Karbi Anglong", "Baithalangso", "Rongkhang"],
                "Karimganj": ["Karimganj", "Badarpur", "Hailakandi", "Katlicherra"],
                "Kokrajhar": ["Kokrajhar", "Gossaigaon", "Bongaigaon", "Barama"],
                "Lakhimpur": ["Lakhimpur", "North Lakhimpur", "Dhemaji", "Majuli"],
                "Majuli": ["Majuli", "Jorhat", "Mikirang", "Garmur"],
                "Morigaon": ["Morigaon", "Laharighat", "Jagiroad", "Mikirang"],
                "Nagaon": ["Nagaon", "Hojai", "Dhing", "Raha"],
                "Nalbari": ["Nalbari", "Barama", "Barkhetri", "Doulas"],
                "Dima Hasao": ["Haflong", "Maibang", "Umrangso", "Dima Hasao"],
                "Sivasagar": ["Sivasagar", "Jorhat", "Nazira", "Charaideo"],
                "Sonitpur": ["Tezpur", "Gohpur", "Nalbari", "Biswanath"],
                "South Salmara-Mankachar": ["Mankachar", "South Salmara", "Nabagram", "Dhubri"],
                "Tinsukia": ["Tinsukia", "Digboi", "Margherita", "Dibrugarh"],
                "Udalguri": ["Udalguri", "Tamulpur", "Bhergaon", "Kachumara"],
                "West Karbi Anglong": ["Hamren", "Kheroni", "Khitam", "Bokajan"],
                "Biswanath": ["Biswanath Chariali", "Gohpur", "Borbari", "Biswanath"]
            },
            "Bihar": {
                "Araria": ["Araria", "Forbesganj", "Jokihat", "Palasi"],
                "Arwal": ["Arwal", "Kaler", "Sasaura", "Kurtha"],
                "Aurangabad": ["Aurangabad", "Barun", "Rafiganj", "Goh"],
                "Banka": ["Banka", "Katoria", "Rampur", "Amjora"],
                "Begusarai": ["Begusarai", "Bakhri", "Nawada", "Chhaurahi"],
                "Bhagalpur": ["Bhagalpur", "Sultanganj", "Kahalgaon", "Naugachia"],
                "Bhojpur": ["Arrah", "Charpokhari", "Piro", "Jagdishpur"],
                "Buxar": ["Buxar", "Dumraon", "Chausa", "Sikaria"],
                "Darbhanga": ["Darbhanga", "Jhanjharpur", "Benipur", "Pursa"],
                "Gaya": ["Gaya", "Sherghati", "Kaler", "Rafiganj"],
                "Gopalganj": ["Gopalganj", "Maharajganj", "Saran", "Bachhwara"],
                "Jamui": ["Jamui", "Ladhaura", "Sikandra", "Chakai"],
                "Jehanabad": ["Jehanabad", "Makhdumpur", "Kuchaikote", "Hulasganj"],
                "Kaimur": ["Bhabhua", "Kaimur", "Kudra", "Nauhatta"],
                "Katihar": ["Katihar", "Purnea", "Kursa", "Manihari"],
                "Kishanganj": ["Kishanganj", "Bahadurganj", "Khargram", "Pothia"],
                "Lakhisarai": ["Lakhisarai", "Munger", "Jamalpur", "Saraiyahat"],
                "Madhepura": ["Madhepura", "Madhubani", "Uda Kishanganj", "Singheshwar"],
                "Madhubani": ["Madhubani", "Jhanjharpur", "Bettiah", "Panjwara"],
                "Munger": ["Munger", "Lakhisarai", "Sultanpur", "Saraiyahat"],
                "Muzaffarpur": ["Muzaffarpur", "Sitamarhi", "Sheohar", "Kanti"],
                "Nalanda": ["Bihar Sharif", "Rajgir", "Hilsa", "Islampur"],
                "Nawada": ["Nawada", "Kawali", "Roh", "Warisaliganj"],
                "Pashchim Champaran": ["Betia", "Narkatiaganj", "Ramgarh", "Chhatauni"],
                "Patna": ["Patna", "Danapur", "Patliputra", "Fatuha"],
                "Purnia": ["Purnia", "Kursela", "Raghunathganj", "Kusheshwar Asthan"],
                "Rohtas": ["Sasaram", "Dehri", "Kargahar", "Karakat"],
                "Sheikhpura": ["Sheikhpura", "Barh", "Katahari", "Bhagalpur"],
                "Sheohar": ["Sheohar", "Pipra", "Dumra", "Balthi"],
                "Sitamarhi": ["Sitamarhi", "Sursand", "Pipra", "Bairgania"],
                "Siwan": ["Siwan", "Maharajganj", "Saran", "Goriakothi"],
                "Supaul": ["Supaul", "Nirmali", "Sarsabad", "Kishanpur"],
                "Vaishali": ["Hajipur", "Jandaha", "Raghurajpur", "Mahnar"],
                "West Champaran": ["Betia", "Narkatiaganj", "Ramnagar", "Chhatauni"]
            },
            "Chandigarh": {
                "Chandigarh": ["Chandigarh"]
            },
            "Chhattisgarh": {
                "Bilaspur": ["Bilaspur", "Koni", "Ratanpur", "Tara"],
                "Dantewada": ["Dantewada", "Konta", "Bacheli", "Geedam"],
                "Dhamtari": ["Dhamtari", "Kurud", "Nagri", "Sihawa"],
                "Durg": ["Durg", "Bhilai", "Patan", "Dondi"],
                "Gariaband": ["Gariaband", "Mainpur", "Palari", "Raikhera"],
                "Janjgir-Champa": ["Janjgir", "Champa", "Kusmunda", "Nari"],
                "Jashpur": ["Jashpur", "Kunkuri", "Pachpedi", "Bagicha"],
                "Kabirdham": ["Kabirdham", "Kawardha", "Bodla", "Pandaria"],
                "Kanker": ["Kanker", "Dhamtari", "Antagarh", "Kanker"],
                "Korba": ["Korba", "Katghora", "Pali", "Dipka"],
                "Mahasamund": ["Mahasamund", "Saraipali", "Bagbahra", "Pithora"],
                "Mungeli": ["Mungeli", "Lormi", "Mungeli", "Patharia"],
                "Narayanpur": ["Narayanpur", "Orchha", "Kundla", "Kanker"],
                "Raigarh": ["Raigarh", "Koni", "Sariya", "Gharghoda"],
                "Raipur": ["Raipur", "Bhilai", "Patan", "Durg"],
                "Rajnandgaon": ["Rajnandgaon", "Dongargaon", "Khairagarh", "Rajnandgaon"],
                "Sukma": ["Sukma", "Chhindgarh", "Kisli", "Kunta"],
                "Surajpur": ["Surajpur", "Odal", "Surajpur", "Kasdol"],
                "Surguja": ["Surguja", "Ambikapur", "Udaipur", "Rajpur"]
            },
            "Goa": {
                "North Goa": ["Panaji", "Mapusa", "Aldona", "Pernem"],
                "South Goa": ["Margao", "Vasco da Gama", "Ponda", "Quepem"]
            },
            "Gujarat": {
                "Ahmedabad": ["Ahmedabad", "Gandhinagar", "Sanand", "Narmada"],
                "Amreli": ["Amreli", "Savarkundla", "Rajula", "Gariadhar"],
                "Anand": ["Anand", "Kheda", "Nadiad", "Vallabh Vidyanagar"],
                "Banaskantha": ["Palanpur", "Deesa", "Tharad", "Dantiwada"],
                "Bharuch": ["Bharuch", "Ankleshwar", "Jambusar", "Vagra"],
                "Bhavnagar": ["Bhavnagar", "Gariadhar", "Mahuva", "Valsad"],
                "Dahod": ["Dahod", "Jhalod", "Limkheda", "Pahadpur"],
                "Gir Somnath": ["Veraval", "Gir", "Junagadh", "Somnath"],
                "Jamnagar": ["Jamnagar", "Rajkot", "Gondal", "Kalavad"],
                "Junagadh": ["Junagadh", "Keshod", "Upleta", "Mangrol"],
                "Kutch": ["Bhuj", "Mandvi", "Nakhtrana", "Anjar"],
                "Mahesana": ["Mahesana", "Patan", "Siddhpur", "Unjha"],
                "Narmada": ["Rajpipla", "Dediapada", "Narmada", "Kevadia"],
                "Navsari": ["Navsari", "Valsad", "Bilimora", "Palsana"],
                "Patan": ["Patan", "Siddhpur", "Unjha", "Radhanpur"],
                "Porbandar": ["Porbandar", "Kutiyana", "Madhavpur", "Bachau"],
                "Sabarkantha": ["Himmatnagar", "Palanpur", "Talod", "Khedbrahma"],
                "Surat": ["Surat", "Gopi", "Olpad", "Bardoli"],
                "Tapi": ["Vyara", "Tapi", "Upleta", "Mandvi"],
                "Vadodara": ["Vadodara", "Gujarat", "Dabhoi", "Chhota Udepur"],
                "Valsad": ["Valsad", "Vapi", "Pardi", "Killa Pardi"]
            },
            "Haryana": {
                "Ambala": ["Ambala", "Naraingarh", "Panchkula", "Saha"],
                "Bhiwani": ["Bhiwani", "Charkhi Dadri", "Bhiwani", "Loharu"],
                "Faridabad": ["Faridabad", "Ballabgarh", "Badkhal", "Tigaon"],
                "Fatehabad": ["Fatehabad", "Tohana", "Ratia", "Beri"],
                "Gurugram": ["Gurugram", "Pataudi", "Sohna", "Manesar"],
                "Hisar": ["Hisar", "Uklana", "Narnaund", "Barwala"],
                "Jind": ["Jind", "Narwana", "Safidon", "Uchana"],
                "Kaithal": ["Kaithal", "Taraori", "Pundri", "Guhla"],
                "Karnal": ["Karnal", "Gharaunda", "Nilokheri", "Assandh"],
                "Mahendragarh": ["Mahendragarh", "Narnaul", "Ateli", "Kanina"],
                "Panchkula": ["Panchkula", "Pinjore", "Chandimandir", "Zirakpur"],
                "Panipat": ["Panipat", "Samalkha", "Karnal", "Gohana"],
                "Rewari": ["Rewari", "Bawal", "Kosli", "Jatusana"],
                "Sirsa": ["Sirsa", "Rania", "Odhan", "Ellenabad"],
                "Sonipat": ["Sonipat", "Ganaur", "Kharkhoda", "Gohana"],
                "Yamunanagar": ["Yamunanagar", "Jagadhri", "Bilaspur", "Radaur"]
            },
            "Himachal Pradesh": {
                "Bilaspur": ["Bilaspur", "Ghumarwin", "Jhandutta", "Sadar"],
                "Chamba": ["Chamba", "Bhattiyat", "Salooni", "Pangi"],
                "Hamirpur": ["Hamirpur", "Nadaun", "Bijhari", "Bani"],
                "Kangra": ["Dharamshala", "Kangra", "Palampur", "Nagrota Bagwan"],
                "Kinnaur": ["Reckong Peo", "Kinnaur", "Kalpa", "Sangla"],
                "Kullu": ["Kullu", "Manali", "Bhuntar", "Naggar"],
                "Lahaul and Spiti": ["Keylong", "Spiti", "Kaza", "Udaipur"],
                "Mandi": ["Mandi", "Sundernagar", "Padhar", "Jogindernagar"],
                "Shimla": ["Shimla", "Kufri", "Mashobra", "Naldehra"],
                "Sirmaur": ["Nahan", "Paonta Sahib", "Rajgarh", "Sadar"],
                "Solan": ["Solan", "Kasauli", "Parwanoo", "Baddi"],
                "Una": ["Una", "Haroli", "Amb", "Bangana"]
            },
            "Jammu and Kashmir": {
                "Jammu": ["Jammu", "Udhampur", "Kathua", "Samba"],
                "Kathua": ["Kathua", "Billawar", "Hiranagar", "Lakhanpur"],
                "Poonch": ["Poonch", "Surankote", "Mendhar", "Rajouri"],
                "Rajouri": ["Rajouri", "Jhangar", "Nowshera", "Kotranka"],
                "Reasi": ["Reasi", "Arnas", "Mahore", "Pouni"],
                "Samba": ["Samba", "Vijaypur", "Nathwal", "Gagwal"],
                "Srinagar": ["Srinagar", "Ganderbal", "Budgam", "Pulwama"],
                "Anantnag": ["Anantnag", "Bijbehara", "Kokernag", "Mattan"],
                "Baramulla": ["Baramulla", "Sopore", "Pattan", "Kupwara"],
                "Bandipora": ["Bandipora", "Sumbal", "Gurez", "Naras"],
                "Kulgam": ["Kulgam", "Qazigund", "Yaripora", "Pahalgam"],
                "Pulwama": ["Pulwama", "Tral", "Awantipora", "Rajpora"],
                "Jammu": ["Jammu", "Nagar", "R.S. Pura", "Chhamb"],
                "Kashmir": ["Srinagar", "Ganderbal", "Pulwama", "Budgam"]
            },
            "Jharkhand": {
                "Bokaro": ["Bokaro", "Chas", "Bermo", "Phusro"],
                "Chatra": ["Chatra", "Pachamba", "Gumla", "Simaria"],
                "Deoghar": ["Deoghar", "Madhupur", "Jarmundi", "Bausi"],
                "Dhanbad": ["Dhanbad", "Jharia", "Sindri", "Kusumgram"],
                "Dumka": ["Dumka", "Jama", "Saraiyahat", "Masanjor"],
                "East Singhbhum": ["Jamshedpur", "Ghatsila", "Dhalbhumgarh", "Seraikela"],
                "Garhwa": ["Garhwa", "Ranka", "Pandu", "Nagar"],
                "Giridih": ["Giridih", "Jamalpur", "Kumaradih", "Rajdhanwar"],
                "Godda": ["Godda", "Madhupur", "Sunderpahari", "Bhagalpur"],
                "Gumla": ["Gumla", "Bariyatu", "Ranchi", "Khunti"],
                "Hazaribagh": ["Hazaribagh", "Barkagaon", "Barhi", "Ichak"],
                "Jamtara": ["Jamtara", "Nali", "Kumargram", "Paharua"],
                "Koderma": ["Koderma", "Jhumri Telaiya", "Satgawan", "Koderma"],
                "Latehar": ["Latehar", "Manika", "Balumath", "Chhatarpur"],
                "Lohardaga": ["Lohardaga", "Bariatu", "Ranchi", "Kuru"],
                "Pakur": ["Pakur", "Hiranpur", "Pakur", "Maheshpur"],
                "Palamu": ["Palamu", "Daltonganj", "Chhatarpur", "Manatu"],
                "Ranchi": ["Ranchi", "Bariatu", "Hatu", "Kanke"],
                "Sahibganj": ["Sahibganj", "Rajmahal", "Maharajpur", "Barharwa"],
                "Seraikela-Kharsawan": ["Seraikela", "Kharsawan", "Kuchai", "Gamharia"],
                "West Singhbhum": ["Chaibasa", "Jagannathpur", "Munda", "Gumla"]
            },
            "Karnataka": {
                "Bagalkot": ["Bagalkot", "Badami", "Bailhongal", "Jamkhandi"],
                "Ballari": ["Ballari", "Hospet", "Sandur", "Bellary"],
                "Belagavi": ["Belagavi", "Hubballi", "Dharwad", "Gokak"],
                "Bengaluru": ["Bengaluru", "Whitefield", "Koramangala", "Electronic City"],
                "Bidar": ["Bidar", "Basavakalyan", "Bhalki", "Humnabad"],
                "Chamarajanagar": ["Chamarajanagar", "Kollegal", "Yelandur", "Nanjangud"],
                "Chikballapur": ["Chikballapur", "Bagepalli", "Chintamani", "Gudibanda"],
                "Chikmagalur": ["Chikmagalur", "Kadur", "Tarikere", "Kadur"],
                "Dakshina Kannada": ["Mangaluru", "Udupi", "Puttur", "Sullia"],
                "Davangere": ["Davangere", "Harihar", "Jagalur", "Honnali"],
                "Dharwad": ["Dharwad", "Hubballi", "Navalgund", "Kalghatgi"],
                "Gadag": ["Gadag", "Gajendragarh", "Laxmeshwar", "Koppal"],
                "Hassan": ["Hassan", "Channarayapatna", "Arasikere", "Belur"],
                "Haveri": ["Haveri", "Hubli", "Savanur", "Ranebennur"],
                "Kodagu": ["Madikeri", "Somwarpet", "Virajpet", "Napoklu"],
                "Kolar": ["Kolar", "Mulbagal", "K.G.F.", "Malur"],
                "Koppal": ["Koppal", "Gangavati", "Kustagi", "Yelburga"],
                "Mandya": ["Mandya", "Srirangapatna", "Maddur", "Krishna Raja Pet"],
                "Mysuru": ["Mysuru", "Hunsur", "Nanjangud", "K.R. Nagar"],
                "Raichur": ["Raichur", "Lingasugur", "Manvi", "Devadurga"],
                "Ramanagara": ["Ramanagara", "Channapatna", "Kanakapura", "Magadi"],
                "Shimoga": ["Shimoga", "Sagar", "Shikaripur", "Bhadravathi"],
                "Tumkur": ["Tumkur", "Tiptur", "Pavagada", "Kunigal"],
                "Udupi": ["Udupi", "Kundapura", "Manipal", "Karkala"],
                "Yadgir": ["Yadgir", "Shorapur", "Gulbarga", "Jevargi"]
            },
            "Kerala": {
                "Alappuzha": ["Alappuzha", "Cherthala", "Kuttanadu", "Mannanchery"],
                "Ernakulam": ["Kochi", "Muvattupuzha", "Aluva", "Perumbavoor"],
                "Idukki": ["Idukki", "Munnar", "Peermade", "Devikulam"],
                "Kannur": ["Kannur", "Thalassery", "Payyannur", "Sreekandapuram"],
                "Kasaragod": ["Kasaragod", "Sullia", "Manjeshwar", "Kanhangad"],
                "Kollam": ["Kollam", "Kottarakkara", "Chavara", "Paravur"],
                "Kottayam": ["Kottayam", "Changanassery", "Pala", "Meenachipalam"],
                "Kozhikode": ["Kozhikode", "Vadakara", "Quilandy", "Kunnamangalam"],
                "Malappuram": ["Malappuram", "Perinthalmanna", "Tirur", "Kondotty"],
                "Palakkad": ["Palakkad", "Ottapalam", "Chittur", "Mannarkkad"],
                "Pathanamthitta": ["Pathanamthitta", "Thiruvalla", "Adoor", "Ranni"],
                "Thrissur": ["Thrissur", "Kodungallur", "Chalakudy", "Irinjalakuda"],
                "Wayanad": ["Wayanad", "Kalpetta", "Sultan Bathery", "Mananthavady"]
            },
            "Ladakh": {
                "Leh": ["Leh", "Nubra", "Sham", "Zanskar"],
                "Kargil": ["Kargil", "Zanskar", "Dras", "Kargil"]
            },
            "Lakshadweep": {
                "Lakshadweep": ["Kavaratti", "Minicoy", "Agatti", "Andrott"]
            },
            "Delhi": {
                "Central Delhi": ["Connaught Place", "Chandni Chowk", "Rajendra Place", "Paharganj"],
                "East Delhi": ["Preet Vihar", "Mayur Vihar", "Laxmi Nagar", "Vikas Marg"],
                "New Delhi": ["New Delhi", "India Gate", "Connaught Place", "Rajpath"],
                "North Delhi": ["Sadar Bazar", "Model Town", "Roop Nagar", "Burari"],
                "South Delhi": ["Hauz Khas", "Greater Kailash", "Vasant Kunj", "Safdarjung"],
                "West Delhi": ["Rajouri Garden", "Dwarka", "Janakpuri", "Uttam Nagar"]
            },
            "Puducherry": {
                "Puducherry": ["Puducherry", "Yanam", "Karaikal", "Mahe"]
            },
            "Punjab": {
                "Amritsar": ["Amritsar", "Tarn Taran", "Ajnala", "Majitha"],
                "Barnala": ["Barnala", "Mansa", "Sardulgarh", "Rampura"],
                "Bathinda": ["Bathinda", "Mandi Dabwali", "Rampura", "Faridkot"],
                "Fatehgarh Sahib": ["Fatehgarh Sahib", "Sirhind", "Khamano", "Amloh"],
                "Ferozepur": ["Ferozepur", "Zira", "Ferozepur City", "Guru Har Sahai"],
                "Gurdaspur": ["Gurdaspur", "Pathankot", "Dera Baba Nanak", "Batala"],
                "Hoshiarpur": ["Hoshiarpur", "Dasuya", "Mukerian", "Nawanshahr"],
                "Jalandhar": ["Jalandhar", "Kapurthala", "Phagwara", "Kartarpur"],
                "Kapurthala": ["Kapurthala", "Sultanpur Lodhi", "Phagwara", "Kapurthala"],
                "Ludhiana": ["Ludhiana", "Mandi Ahmedgarh", "Khamano", "Raikot"],
                "Mansa": ["Mansa", "Budhlada", "Nabha", "Maur"],
                "Mohali": ["Mohali", "Kharar", "S.A.S. Nagar", "Zirakpur"],
                "Pathankot": ["Pathankot", "Dera Baba Nanak", "Sujanpur", "Maharana"],
                "Rupnagar": ["Rupnagar", "Nangal", "Kiratpur Sahib", "Rupnagar"],
                "Sangrur": ["Sangrur", "Malerkotla", "Dhuri", "Barnala"],
                "Shaheed Bhagat Singh Nagar": ["Shaheed Bhagat Singh Nagar", "Nawanshahr", "Rupnagar", "Banga"],
                "Tarn Taran": ["Tarn Taran", "Chheharta", "Khemkaran", "Guru Harsahai"]
            },
            "Rajasthan": {
                "Ajmer": ["Ajmer", "Kishangarh", "Beawar", "Nasirabad"],
                "Alwar": ["Alwar", "Bhiwadi", "Kishangarh", "Raniwara"],
                "Banswara": ["Banswara", "Mandalgarh", "Sagwara", "Ghatol"],
                "Barmer": ["Barmer", "Balotra", "Jaisalmer", "Barmer"],
                "Bhilwara": ["Bhilwara", "Chittorgarh", "Mandalgarh", "Rajsamand"],
                "Bikaner": ["Bikaner", "Pugal", "Deshnok", "Nagaur"],
                "Chittorgarh": ["Chittorgarh", "Nimbahera", "Ratanpur", "Bhilwara"],
                "Dausa": ["Dausa", "Lalsot", "Rajasthan", "Bassi"],
                "Dholpur": ["Dholpur", "Rajakhera", "Kachhawa", "Rajasthan"],
                "Dungarpur": ["Dungarpur", "Sagwara", "Mandalgarh", "Ghatol"],
                "Hanumangarh": ["Hanumangarh", "Nohar", "Sangaria", "Sadulshahar"],
                "Jaipur": ["Jaipur", "Sanganer", "Vidhyadhar Nagar", "Malviya Nagar"],
                "Jaisalmer": ["Jaisalmer", "Sam", "Barmer", "Jodhpur"],
                "Jalore": ["Jalore", "Raniwara", "Sirohi", "Jalore"],
                "Jhalawar": ["Jhalawar", "Kota", "Baran", "Kishangarh"],
                "Jhunjhunu": ["Jhunjhunu", "Sikar", "Churu", "Rajgarh"],
                "Jodhpur": ["Jodhpur", "Pali", "Osian", "Bundi"],
                "Karauli": ["Karauli", "Hindaun", "Todabhim", "Nagar"],
                "Nagaur": ["Nagaur", "Merta", "Didwana", "Kuchaman"],
                "Pali": ["Pali", "Rohat", "Sojat", "Marwar"],
                "Rajsamand": ["Rajsamand", "Kankroli", "Nathdwara", "Kumbhalgarh"],
                "Sawai Madhopur": ["Sawai Madhopur", "Kota", "Baran", "Rajasthan"],
                "Sikar": ["Sikar", "Danta Ramgarh", "Laxmangarh", "Rajasthan"],
                "Tonk": ["Tonk", "Malpura", "Deoli", "Aligarh"],
                "Udaipur": ["Udaipur", "Rajsamand", "Kumbhalgarh", "Chittorgarh"]
            }
        }
function populateStates() {
    const stateSelect = document.getElementById("state");
    Object.keys(stateDistrictMap).forEach(function (state) {
        const option = document.createElement("option");
        option.value = state;
        option.text = state;
        stateSelect.appendChild(option);
    });
  }

  // Function to populate districts dropdown based on selected state
  function populateDistricts() {
    const stateSelect = document.getElementById("state");
    const districtSelect = document.getElementById("district");
    const placeSelect = document.getElementById("place");

    const selectedState = stateSelect.value;
    districtSelect.innerHTML = '<option value="">Select District</option>';
    placeSelect.innerHTML = '<option value="">Select Place</option>';

    if (selectedState && stateDistrictMap[selectedState]) {
        Object.keys(stateDistrictMap[selectedState]).forEach(function (district) {
            const option = document.createElement("option");
            option.value = district;
            option.text = district;
            districtSelect.appendChild(option);
        });
    }
  }

  // Function to populate places dropdown based on selected district
  function populatePlaces() {
    const stateSelect = document.getElementById("state");
    const districtSelect = document.getElementById("district");
    const placeSelect = document.getElementById("place");

    const selectedState = stateSelect.value;
    const selectedDistrict = districtSelect.value;
    placeSelect.innerHTML = '<option value="">Select Place</option>';

    if (selectedState && selectedDistrict && stateDistrictMap[selectedState][selectedDistrict]) {
        stateDistrictMap[selectedState][selectedDistrict].forEach(function (place) {
            const option = document.createElement("option");
            option.value = place;
            option.text = place;
            placeSelect.appendChild(option);
        });
    }
  }

  // Initialize the states dropdown when the page loads
  window.onload = function () {
    populateStates();
  };
    </script>

</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="./index1.php">RealEstimate</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="./index1.php">Home</a>
        </li>
        
      </ul>
      
    </div>
  </div>
</nav>
    <header>
        <h1>Upload Plot for Buy</h1>
    </header>
    <div class="upload-container">
        <form action="upload_plot_buy.php" method="post" enctype="multipart/form-data">
    <!-- State Dropdown -->
    <div class="form-group">
        <label for="state">State:</label>
        <select id="state" name="state" required onchange="populateDistricts()">
            <option value="">Select State</option>
        </select>
    </div>

    <!-- District Dropdown -->
    <div class="form-group">
        <label for="district">District:</label>
        <select id="district" name="district" required onchange="populatePlaces()">
            <option value="">Select District</option>
        </select>
    </div>

    <!-- Place Dropdown -->
    <div class="form-group">
        <label for="place">Place:</label>
        <select id="place" name="place" required>
            <option value="">Select Place</option>
        </select>
    </div>

            <div class="form-group">
                <label for="size">Size (in sq. ft.):</label>
                <input type="text" id="size" name="size" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required>
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>
            <input type="submit" value="Upload House">
        </form>
    </div>
</body>
</html>
