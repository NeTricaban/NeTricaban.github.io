<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Эмулятор Android на PHP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', Arial, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 1200px;
        }
        
        h1 {
            color: white;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 300;
            text-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .phone-container {
            position: relative;
            width: 360px;
            height: 740px;
            margin: 0 auto;
        }
        
        .phone {
            position: relative;
            width: 100%;
            height: 100%;
            background: #111;
            border-radius: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            overflow: hidden;
            border: 8px solid #222;
        }
        
        .phone-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 30px;
            background: #111;
            border-radius: 40px 40px 0 0;
            z-index: 10;
        }
        
        .screen {
            position: absolute;
            top: 30px;
            left: 0;
            width: 100%;
            height: calc(100% - 60px);
            background: linear-gradient(135deg, #8a2be2 0%, #4b0082 100%);
            overflow: hidden;
        }
        
        .status-bar {
            height: 25px;
            background: rgba(0,0,0,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 15px;
            color: white;
            font-size: 12px;
        }
        
        .time {
            font-weight: bold;
        }
        
        .status-icons {
            display: flex;
            gap: 5px;
        }
        
        .home-screen {
            padding: 20px;
            height: calc(100% - 25px);
        }
        
        .date-widget {
            color: white;
            margin-bottom: 30px;
        }
        
        .date {
            font-size: 24px;
            font-weight: 300;
        }
        
        .time-large {
            font-size: 48px;
            font-weight: 100;
        }
        
        .apps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .app {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .app:hover {
            transform: scale(1.1);
        }
        
        .app-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .app-name {
            font-size: 10px;
            text-align: center;
        }
        
        .dock {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-around;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 10px;
            backdrop-filter: blur(10px);
        }
        
        .phone-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30px;
            background: #111;
            border-radius: 0 0 40px 40px;
            z-index: 10;
        }
        
        .controls {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }
        
        button {
            background: rgba(255,255,255,0.2);
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: background 0.3s;
        }
        
        button:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .app-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            transform: translateY(100%);
            transition: transform 0.3s;
            z-index: 20;
        }
        
        .app-screen.active {
            transform: translateY(0);
        }
        
        .app-header {
            height: 50px;
            background: #6200ee;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 15px;
            position: relative;
        }
        
        .back-button {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            padding: 5px;
        }
        
        .app-content {
            padding: 20px;
            height: calc(100% - 50px);
            overflow-y: auto;
        }
        
        .message {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        
        .message.user {
            background: #e3f2fd;
            margin-left: 20px;
        }
        
        .camera-view {
            background: #000;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
        }
        
        .gallery-item {
            aspect-ratio: 1;
            background: #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Эмулятор Android на PHP</h1>
        
        <div class="phone-container">
            <div class="phone">
                <div class="phone-top"></div>
                
                <div class="screen">
                    <div class="status-bar">
                        <div class="time"><?php echo date('H:i'); ?></div>
                        <div class="status-icons">
                            <span>📶</span>
                            <span>📱</span>
                            <span>🔋</span>
                        </div>
                    </div>
                    
                    <div class="home-screen">
                        <div class="date-widget">
                            <div class="date"><?php echo date('l, j F'); ?></div>
                            <div class="time-large"><?php echo date('H:i'); ?></div>
                        </div>
                        
                        <div class="apps-grid">
                            <?php
                            $apps = [
                                ['name' => 'Телефон', 'icon' => '📞', 'color' => '#4CAF50'],
                                ['name' => 'Сообщения', 'icon' => '💬', 'color' => '#2196F3'],
                                ['name' => 'Камера', 'icon' => '📷', 'color' => '#FF9800'],
                                ['name' => 'Галерея', 'icon' => '🖼️', 'color' => '#E91E63'],
                                ['name' => 'Chrome', 'icon' => '🌐', 'color' => '#4285F4'],
                                ['name' => 'Карты', 'icon' => '🗺️', 'color' => '#34A853'],
                                ['name' => 'Музыка', 'icon' => '🎵', 'color' => '#9C27B0'],
                                ['name' => 'Настройки', 'icon' => '⚙️', 'color' => '#607D8B'],
                                ['name' => 'Календарь', 'icon' => '📅', 'color' => '#FF5722'],
                                ['name' => 'Часы', 'icon' => '⏰', 'color' => '#009688'],
                                ['name' => 'Почта', 'icon' => '📧', 'color' => '#FFC107'],
                                ['name' => 'Play Маркет', 'icon' => '🛒', 'color' => '#00BCD4']
                            ];
                            
                            foreach ($apps as $app) {
                                echo "
                                <div class='app' onclick=\"openApp('{$app['name']}')\">
                                    <div class='app-icon' style='background: {$app['color']}'>
                                        {$app['icon']}
                                    </div>
                                    <div class='app-name'>{$app['name']}</div>
                                </div>";
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="dock">
                        <?php
                        $dockApps = [
                            ['name' => 'Телефон', 'icon' => '📞'],
                            ['name' => 'Сообщения', 'icon' => '💬'],
                            ['name' => 'Камера', 'icon' => '📷'],
                            ['name' => 'Chrome', 'icon' => '🌐']
                        ];
                        
                        foreach ($dockApps as $app) {
                            echo "
                            <div class='app' onclick=\"openApp('{$app['name']}')\">
                                <div class='app-icon' style='background: rgba(255,255,255,0.2)'>
                                    {$app['icon']}
                                </div>
                            </div>";
                        }
                        ?>
                    </div>
                </div>
                
                <div class="phone-bottom"></div>
                
                <!-- App Screens -->
                <div id="messagesApp" class="app-screen">
                    <div class="app-header">
                        <button class="back-button" onclick="closeApp('messagesApp')">←</button>
                        <div style="margin-left: 15px;">Сообщения</div>
                    </div>
                    <div class="app-content">
                        <div class="message">Привет! Как дела?</div>
                        <div class="message user">Отлично, спасибо! А у тебя?</div>
                        <div class="message">Тоже хорошо! Что нового?</div>
                        <div class="message user">Просто тестирую этот эмулятор Android</div>
                        <div class="message">Круто выглядит! 😊</div>
                    </div>
                </div>
                
                <div id="cameraApp" class="app-screen">
                    <div class="app-header">
                        <button class="back-button" onclick="closeApp('cameraApp')">←</button>
                        <div style="margin-left: 15px;">Камера</div>
                    </div>
                    <div class="app-content">
                        <div class="camera-view">
                            <div>📷 Вид с камеры</div>
                        </div>
                    </div>
                </div>
                
                <div id="galleryApp" class="app-screen">
                    <div class="app-header">
                        <button class="back-button" onclick="closeApp('galleryApp')">←</button>
                        <div style="margin-left: 15px;">Галерея</div>
                    </div>
                    <div class="app-content">
                        <div class="gallery-grid">
                            <?php
                            for ($i = 1; $i <= 9; $i++) {
                                echo "<div class='gallery-item'>Фото $i</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="controls">
            <button onclick="updateTime()">Обновить время</button>
            <button onclick="lockScreen()">Заблокировать</button>
            <button onclick="restart()">Перезагрузить</button>
        </div>
    </div>

    <script>
        function openApp(appName) {
            const appScreens = {
                'Сообщения': 'messagesApp',
                'Камера': 'cameraApp',
                'Галерея': 'galleryApp'
            };
            
            const screenId = appScreens[appName];
            if (screenId) {
                document.getElementById(screenId).classList.add('active');
            } else {
                alert(`Приложение "${appName}" открыто!`);
            }
        }
        
        function closeApp(appId) {
            document.getElementById(appId).classList.remove('active');
        }
        
        function updateTime() {
            const now = new Date();
            document.querySelector('.time').textContent = now.toLocaleTimeString('ru-RU', {hour: '2-digit', minute:'2-digit'});
            document.querySelector('.time-large').textContent = now.toLocaleTimeString('ru-RU', {hour: '2-digit', minute:'2-digit'});
            
            const options = { weekday: 'long', day: 'numeric', month: 'long' };
            document.querySelector('.date').textContent = now.toLocaleDateString('ru-RU', options);
        }
        
        function lockScreen() {
            alert('Экран заблокирован!');
        }
        
        function restart() {
            if (confirm('Перезагрузить эмулятор?')) {
                location.reload();
            }
        }
        
        // Обновляем время каждую минуту
        setInterval(updateTime, 60000);
    </script>
</body>
</html>
