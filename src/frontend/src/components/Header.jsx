import { Typography, Box, useTheme } from "@mui/material";
import { tokens } from "../theme";

const Header = ({ title, subtitle }) => {
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);
  return (
    <Box mt="20px">
      <Typography
        variant="h2"
        color={colors.grey[100]}
        fontWeight="bold"
        margin="10px 0"
      >
        {title}
      </Typography>
      <Typography variant="h5" color={colors.goldAccent[300]}>
        {subtitle}
      </Typography>
    </Box>
  );
};

export default Header;
