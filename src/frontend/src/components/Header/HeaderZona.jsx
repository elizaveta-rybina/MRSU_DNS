import AddIcon from "@mui/icons-material/Add";
import DeleteOutlinedIcon from "@mui/icons-material/DeleteOutlined";
import { Box, useTheme } from "@mui/material";
import Button from "@mui/material/Button";
import { tokens } from "../../theme";

const HeaderZona = ({
  addInscription,
  deleteInscription,
  handleClick,
  handleDeleteAll,
}) => {
  const theme = useTheme();
  const colors = tokens(theme.palette.mode);
  return (
    <Box>
      <Button
        component="label"
        startIcon={<AddIcon />}
        onClick={handleClick}
        sx={{
          margin: "20px 5px",
          border: "1px solid",
          color: colors.grey[100],
        }}
      >
        {addInscription}
        <input multiple type="file" hidden />
      </Button>
      <Button
        color="primary"
        startIcon={<DeleteOutlinedIcon />}
        onClick={handleDeleteAll}
        sx={{
          margin: "20px 5px",
          border: "1px solid",
          color: colors.grey[100],
        }}
      >
        {deleteInscription}
      </Button>
    </Box>
  );
};

export default HeaderZona;
