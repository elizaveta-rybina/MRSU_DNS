import ArticleOutlinedIcon from "@mui/icons-material/ArticleOutlined";
import DnsOutlinedIcon from "@mui/icons-material/DnsOutlined";
import InfoOutlinedIcon from "@mui/icons-material/InfoOutlined";
import PersonOutlinedIcon from "@mui/icons-material/PersonOutlined";
import { Box } from "@mui/material";
import ActiveUsers from "../../components/Dashboard/ActiveUsers";
import CustomBox from "../../components/Dashboard/CustomBox";
import Domains from "../../components/Dashboard/Domains";

const Dashboard = () => {
  const boxes = [
    {
      mr: 3,
      icon: <PersonOutlinedIcon sx={{ fontSize: 40 }} />,
      title: "Пользователями",
      link: "/users",
    },
    {
      mr: 3,
      icon: <DnsOutlinedIcon sx={{ fontSize: 40 }} />,
      title: "Доменами",
      link: "/domain",
    },
    {
      mr: 3,
      icon: <ArticleOutlinedIcon sx={{ fontSize: 40 }} />,
      title: "Файлами зоны",
      link: "/zona",
    },
    {
      mr: 1,
      icon: <InfoOutlinedIcon sx={{ fontSize: 40 }} />,
      title: "DNS-записями",
      link: "/records/1",
    },
  ];

  return (
    <Box
      sx={{
        display: "flex",
        flexWrap: "wrap",
        height: "80vh",
        width: "auto",
        mx: {
          xs: 1,
          sm: 2,
          md: 3,
          lg: 4,
          xl: 5,
        },
      }}
    >
      <Box
        sx={{
          flex: "0 0 69%",
          mr: 3,
          display: "flex",
          flexFlow: "column wrap",
        }}
      >
        <Box
          sx={{
            mb: 2,
            flex: 0.7,
            display: "flex",
            justifyContent: "space-between",
            flexFlow: "row nowrap",
          }}
        >
          {boxes.map((boxProps, index) => (
            <CustomBox key={index} {...boxProps} />
          ))}
        </Box>
        <Box
          sx={{
            flex: 1.3,
          }}
        >
          <Domains title="Последние измененные домены" />
        </Box>
      </Box>
      <Box
        sx={{
          flex: "0 0 28%",
        }}
      >
        <ActiveUsers title="Активные пользователи"/>
      </Box>
    </Box>
  );
};

export default Dashboard;
