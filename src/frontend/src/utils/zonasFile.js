import { Box } from "@mui/material";
import React, { useEffect, useState } from "react";
import { fileZona } from "../data/mockData";

const ZoneFileReader = () => {
  const [zoneData, setZoneData] = useState(null);

  useEffect(() => {
    const fetchZoneData = async () => {
      try {
        setZoneData(fileZona);
      } catch (error) {
        console.error("Ошибка при загрузке файла зоны:", error);
      }
    };
    fetchZoneData();
  }, []); // [] - пустой массив зависимостей, чтобы useEffect сработал только при монтировании

  if (!zoneData) {
    return <div>Загрузка данных файла зоны...</div>;
  }

  console.log(zoneData.records);

  // Преобразование данных файла зоны в текстовый формат
  let zoneFileContent = `$TTL ${zoneData.ttl}\n`;

  zoneData.records.forEach((record) => {
    if (record.type === "SOA") {
      zoneFileContent += `${record.name} ${record.ttl} IN SOA ${record.primary} ${record.admin} (\n`;
      zoneFileContent += `\t\t\t${record.serial} ; serial\n`;
      zoneFileContent += `\t\t\t${record.refresh} ; refresh\n`;
      zoneFileContent += `\t\t\t${record.retry} ; retry\n`;
      zoneFileContent += `\t\t\t${record.expire} ; expire\n`;
      zoneFileContent += `\t\t\t${record.minimum} ; minimum\n`;
      zoneFileContent += `\t\t\t)\n`;
      zoneFileContent += `\n`;
    } else if (record.type === "NS") {
      zoneFileContent += `${record.name} ${record.ttl} IN NS ${record.value}\n`;
    } else if (record.type === "A") {
      zoneFileContent += `${record.name} ${record.ttl} IN A ${record.value}\n`;
    } else if (record.type === "CNAME") {
      zoneFileContent += `${record.name} ${record.ttl} IN CNAME ${record.value}\n`;
    } else if (record.type === "MX") {
      zoneFileContent += `${record.name} ${record.ttl} IN MX ${record.priority} ${record.value}\n`;
    } else if (record.type === "TXT") {
      zoneFileContent += `${record.name} ${record.ttl} IN TXT "${record.value}"\n`;
    }
  });

  return (
    <Box
      sx={{
        whiteSpace: "pre-wrap",
      }}
    >
      {zoneFileContent};
    </Box>
  );
};

export default ZoneFileReader;
