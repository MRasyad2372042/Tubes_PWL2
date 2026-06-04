const express = require('express');
const cors = require('cors');

const app = express();

const userRoutes = require('./routes/userRoutes');
const roomRoutes = require('./routes/roomRoutes');
const bhpRoutes = require('./routes/bhpRoutes');
const maintenanceRoutes = require('./routes/maintenanceRoutes');

app.use(cors());
app.use(express.json());

app.get('/', (req, res) => {
    res.json({ message: 'Node backend running' });
});

app.use('/api/users', userRoutes);
app.use('/api/rooms', roomRoutes);
app.use('/api/bhp', bhpRoutes);
app.use('/api/maintenance', maintenanceRoutes);

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});