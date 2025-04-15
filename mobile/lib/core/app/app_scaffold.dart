import 'package:calamitech/config/theme/app_theme.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

import '../../constants/route_constants.dart';
import 'cubit/navigation_cubit.dart';

class AppScaffold extends StatelessWidget {
  final Widget child;

  const AppScaffold({super.key, required this.child});

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<NavigationCubit, int>(
      builder: (context, selectedIndex) {
        return Scaffold(
          appBar: _buildAppBar(selectedIndex),
          body: child,
          bottomNavigationBar: _buildBottomAppBar(context, selectedIndex),
        );
      },
    );
  }

  BottomAppBar _buildBottomAppBar(BuildContext context, int selectedIndex) {
    return BottomAppBar(
      color: Colors.grey[200],
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceEvenly,
        children: [
          _buildNavBarItem(context, Icons.home, 'Home', RouteConstants.home, 0, selectedIndex),
          // _buildSOSNavItem(context, selectedIndex),
          _buildNavBarItem(context, Icons.person, 'Profile', RouteConstants.profile, 2, selectedIndex),
        ],
      ),
    );
  }
}

AppBar _buildAppBar(int selectedIndex) {
  String title;
  switch (selectedIndex) {
    case 0:
      title = 'Home';
      break;
    // case 1:
    //   title = 'Report';
    //   break;
    case 2:
      title = 'Profile';
      break;
    case 3:
      title = 'Emergency SOS';
    default:
      title = 'Home';
  }

  return AppBar(
    centerTitle: true,
    backgroundColor: AppTheme.primaryColor,
    title: Text(title, style: const TextStyle(color: Colors.white)),
  );
}

Widget _buildSOSNavItem(BuildContext context, int selectedIndex) {
  return GestureDetector(
    onTap: () {
      if (GoRouter.of(context).routeInformationProvider.value.uri != RouteConstants.sos) {
        context.read<NavigationCubit>().selectTab(3);
        context.go(RouteConstants.sos);
      }
    },
    child: Container(
      decoration: BoxDecoration(
        color: Colors.red,
        shape: BoxShape.circle,
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.5),
            spreadRadius: 1,
            blurRadius: 3,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      padding: const EdgeInsets.all(8),
      child: const Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(Icons.sos, color: Colors.white, size: 40),
          // Text(
          //   'SOS',
          //   style: TextStyle(
          //     color: Colors.white,
          //     fontWeight: FontWeight.bold,
          //     fontSize: 11,
          //   ),
          // ),
        ],
      ),
    ),
  );
}

Widget _buildNavBarItem(BuildContext context, IconData icon, String label, String route, int index, int selectedIndex) {
  return GestureDetector(
    onTap: () {
      context.read<NavigationCubit>().selectTab(index);
      context.go(route);
    },
    child: Container(
      color: Colors.transparent,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: selectedIndex == index ? AppTheme.primaryColor : Colors.grey),
          Text(
            label,
            style: TextStyle(
              color: selectedIndex == index ? AppTheme.primaryColor : Colors.grey,
            ),
          ),
        ],
      ),
    ),
  );
}
