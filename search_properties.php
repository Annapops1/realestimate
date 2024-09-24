
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Properties - RealEstiMate</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <link rel="stylesheet" href="common.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px 0;
            padding-left: 10%;
        }

        .navbar a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
            margin-right: 10px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 50px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #555;
            font-weight: 500;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
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

    window.onload = function () {
        populateStates();
    };

    function populateStates() {
        const stateSelect = document.getElementById("state");
        Object.keys(stateDistrictMap).forEach(function (state) {
            const option = document.createElement("option");
            option.value = state;
            option.text = state;
            stateSelect.appendChild(option);
        });
    }

    function updateDistricts() {
        const stateSelect = document.getElementById('state');
        const districtSelect = document.getElementById('district');
        const placeSelect = document.getElementById('place');
        const selectedState = stateSelect.value;

        // Clear existing options
        districtSelect.innerHTML = '<option value="">Select District</option>';
        placeSelect.innerHTML = '<option value="">Select Place</option>';

        if (selectedState && stateDistrictMap[selectedState]) {
            for (const district in stateDistrictMap[selectedState]) {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            }
        }
    }

    function updatePlaces() {
        const stateSelect = document.getElementById('state');
        const districtSelect = document.getElementById('district');
        const placeSelect = document.getElementById('place');
        const selectedState = stateSelect.value;
        const selectedDistrict = districtSelect.value;

        placeSelect.innerHTML = '<option value="">Select Place</option>';

        if (selectedState && selectedDistrict && stateDistrictMap[selectedState] && stateDistrictMap[selectedState][selectedDistrict]) {
            stateDistrictMap[selectedState][selectedDistrict].forEach(place => {
                const option = document.createElement('option');
                option.value = place;
                option.textContent = place;
                placeSelect.appendChild(option);
            });
        }
    }
    </script>
</head>
<body>
    <div class="navbar">
        <a href="index1.php">Home</a>
        <a href="my_properties.php">My Properties</a>
        <a href="upload_property.php">Upload Property</a>
        <a href="search_properties.php">Search Properties</a>
        <a href="view_property.php">View Property</a>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
        <a href="contact.php">Contact</a>
    </div>

    <div class="container">
        <h1>Search Properties</h1>
        <form method="POST" action="search_properties.php">
            <label for="state">State</label>
            <select name="state" id="state" onchange="updateDistricts()">
                <option value="">Select State</option>
                <!-- Add more states here -->
            </select>

            <label for="district">District</label>
            <select name="district" id="district" onchange="updatePlaces()">
                <option value="">Select District</option>
            </select>

            <label for="place">Place</label>
            <select name="place" id="place">
                <option value="">Select Place</option>
            </select>

            <button type="submit">Search</button>
        </form>
        <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $state = $_POST['state'];
    $district = $_POST['district'];
    $place = $_POST['place'];

    // Database connection
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "miniproj";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Adjust SQL query based on whether place is selected
    if (!empty($place)) {
        $sql = "SELECT * FROM properties WHERE state='$state' AND district='$district' AND place='$place'";
    } else {
        $sql = "SELECT * FROM properties WHERE state='$state' AND district='$district'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Search Results:</h2>";
        echo "<table id='propertiesTable'>";
        echo "<thead><tr><th>State</th><th>District</th><th>Place</th><th>Price</th><th>View</th></tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["state"] . "</td><td>" . $row["district"] . "</td><td>" . $row["place"] . "</td><td>" . $row["price"] . "</td><td><a href='property_details.php?property_id=" . $row["property_id"] . "' class='btn btn-success'>View</a></td></tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "0 results";
    }

    $conn->close();
}
?>
    </div>
</body>
<script>
    $(document).ready(function() {
        $('#propertiesTable').DataTable({searching: false});
    });
</script>
</html>
